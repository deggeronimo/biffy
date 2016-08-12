<?php namespace Biffy\Services\Entities\MessageBoard;

use Biffy\Commands\MessageBoard\CreatePostCommand;
use Biffy\Commands\MessageBoard\CreateThreadCommand;
use Biffy\Commands\MessageBoard\VotePostCommand;
use Biffy\Entities\BoardCategory\BoardCategoryRepositoryInterface;
use Biffy\Entities\BoardPost\BoardPostRepositoryInterface;
use Biffy\Entities\BoardThread\BoardThreadRepositoryInterface;
use Biffy\Events\ThreadViewed;
use Biffy\Services\Entities\Service;

class MessageBoardService extends Service
{
    /**
     * @var BoardCategoryRepositoryInterface
     */
    private $boardCategoryRepository;

    /**
     * @var BoardThreadRepositoryInterface
     */
    private $boardThreadRepository;

    /**
     * @var BoardPostRepositoryInterface
     */
    private $boardPostRepository;

    public function __construct(BoardCategoryRepositoryInterface $boardCategoryRepository, BoardThreadRepositoryInterface $boardThreadRepository, BoardPostRepositoryInterface $boardPostRepository)
    {
        $this->boardCategoryRepository = $boardCategoryRepository;
        $this->boardThreadRepository = $boardThreadRepository;
        $this->boardPostRepository = $boardPostRepository;
    }

    public function votePost($postId, $rating)
    {
        $this->register(new VotePostCommand(['postId' => $postId, 'rating' => $rating, 'votingUserId' => \Auth::user()->userId()]));
        $this->execute();

        return $this->boardPostRepository->getPost($postId);
    }

    public function getCategories()
    {
        return $this->boardCategoryRepository->getCategories();
    }

    public function getCategory($id)
    {
        return $this->boardCategoryRepository->getCategory($id);
    }

    public function getBoard($id, $page, $perPage, $sort)
    {
        $category = $this->boardCategoryRepository->getBoard($id, $page, $perPage, $sort);
        $parents = $category->getAncestors();
        foreach ($category->children as $k => $child) {
            $category->children[$k]->children = $child->children;
        }
        $board = $category->toArray();
        $board['parents'] = $parents;
        $board['num_threads'] = $this->boardThreadRepository->countInCategory($id);
        return $board;
    }

    public function createCategory($parent, $data)
    {
        return $this->boardCategoryRepository->addCategory($parent, $data);
    }

    public function updateCategory($id, $data)
    {
        return $this->boardCategoryRepository->updateCategory($id, $data);
    }

    public function moveCategory($id, $direction)
    {
        if ($direction !== 'down' && $direction !== 'up') {
            return;
        }

        $category = $this->boardCategoryRepository->find($id);
        $category->$direction();
    }

    public function createThread($categoryId, $title, $postContent)
    {
        $tags = $this->handleTagging($postContent);
        $command = new CreateThreadCommand(['title' => $title, 'content' => $postContent, 'categoryId' => $categoryId, 'userId' => \Auth::user()->userId(), 'tags' => $tags]);
        $this->register($command);
        $this->execute();
        return $command->thread;
    }

    public function editThread($id, $title, $content)
    {
        $thread = $this->boardThreadRepository->find($id);
        $thread->title = $title;
        $thread->save();

        $thread->first_post->content = $content;
        $thread->first_post->save();
    }

    public function getThread($id, $page, $perPage, $sort)
    {
        $thread = $this->boardThreadRepository->getThread($id, $page, $perPage, $sort);
        $thread->category->parents = $thread->category->getAncestors();
        $thread->num_posts = $this->boardPostRepository->countInThread($id);
        return $thread;
    }

    public function getThreadForEdit($id)
    {
        $thread = $this->boardThreadRepository->getThreadForEdit($id);
        $thread->category->parents = $thread->category->getAncestors();
        return $thread;
    }

    public function deleteThread($id)
    {
        $this->boardThreadRepository->delete($id);
    }

    public function threadClosed($id, $closed)
    {
        $this->boardThreadRepository->update($id, ['closed' => $closed]);
    }

    public function moveThread($id, $categoryId)
    {
        $this->boardThreadRepository->update($id, ['category_id' => $categoryId]);
    }

    public function stickyThread($id, $sticky)
    {
        $this->boardThreadRepository->update($id, ['sticky' => $sticky]);
    }

    public function viewThread($id)
    {
        $thread = $this->boardThreadRepository->find($id);
        event(new ThreadViewed($thread, \Auth::user()->userId()));
    }

    public function getPostEdit($id)
    {
        $post = $this->boardPostRepository->getPostEdit($id);
        $post->thread->category->parents = $post->thread->category->getAncestors();
        return $post;
    }

    public function createPost($threadId, $content, $subscribe)
    {
        $tags = $this->handleTagging($content);
        $command = new CreatePostCommand(['threadId' => $threadId, 'content' => $content, 'userId' => \Auth::user()->userId(), 'subscribe' => $subscribe, 'tags' => $tags]);
        $this->register($command);
        $this->execute();
        return $command->post;
    }

    public function editPost($id, $content)
    {
        $this->boardPostRepository->update($id, ['content' => $content]);
    }

    public function deletePost($id)
    {
        $this->boardPostRepository->delete($id);
    }

    public function categoryList()
    {
        $data = [];
        $categoryTree = $this->boardCategoryRepository->categoryTree();
        $this->flatten($categoryTree, $data);
        return $data;
    }

    private function flatten($rawData, &$data, $depth = 0)
    {
        array_walk($rawData, function ($val, $key) use (&$data, $depth) {
                $data[] = ['id' => $val['id'], 'name' => $this->padName($val['name'], $depth)];
                $this->flatten($val['children'], $data, $depth + 1);
            });
    }

    private function padName($name, $depth)
    {
        return str_repeat(config('info.message-boards.category-select-name-pad-string'), $depth * config('info.message-boards.category-select-pad-per-depth')) . $name;
    }

    public function subscribe($threadId, $userId)
    {
        $this->boardThreadRepository->find($threadId)->subscriptions()->attach($userId, ['notify' => true]);
    }

    public function unsubscribe($threadId, $userId)
    {
        $this->boardThreadRepository->find($threadId)->subscriptions()->detach($userId);
    }

    public function getLatestUnreadThreads()
    {
        // 10 of user's latest unread threads
        return $this->boardThreadRepository->getLatestUnread(\Auth::user()->userId(), 10);
    }

    public function activity($userId)
    {
        // todo
    }

    /**
     * @param $content
     * @return array
     */
    private function handleTagging(&$content)
    {
        $tags = [];
        preg_match_all("/\\B@([a-zA-Z]+[.]?[a-zA-Z]*)/", $content, $tags);

        /** @var \Biffy\Services\Entities\User\UserService $userService */
        $userService = app('Biffy\Services\Entities\User\UserService');
        $users = $userService->findByUsernames($tags[1]);

        foreach ($users as $user) {
            $content = str_replace('@' . $user->username, "<a href='/profile/{$user->id}'>@{$user->username}</a>", $content);
        }

        return $users;
    }
} 