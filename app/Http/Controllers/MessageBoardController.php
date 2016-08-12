<?php namespace Biffy\Http\Controllers;

use Biffy\Services\Entities\MessageBoard\MessageBoardService;

class MessageBoardController extends ApiController
{
    /**
     * @var MessageBoardService
     */
    private $service;

    public function __construct(MessageBoardService $service)
    {
        $this->service = $service;
    }

    public function getIndex()
    {
        return $this->data(['boards' => $this->service->getCategories()->toArray(), 'unread' => $this->service->getLatestUnreadThreads()])->respond();
    }

    public function getBoard($id)
    {
        return $this->data($this->service->getBoard($id, $this->input('page', 1), $this->input('perPage', 10), $this->input('sort', 'time')))->respond();
    }

    public function getCategoryList()
    {
        return $this->data($this->service->categoryList())->respond();
    }

    public function getCategory($id)
    {
        return $this->data($this->service->getCategory($id)->toArray())->respond();
    }

    public function postCategory()
    {
        return $this->data($this->service->createCategory($this->input('parent_id'), ['name' => $this->input('name')])->toArray())->statusCreated()->respond();
    }

    public function putCategory($id)
    {
        if ($this->input('move')) {
            $this->service->moveCategory($id, $this->input('move'));
            return;
        }
        $this->service->updateCategory($id, ['name' => $this->input('name'), 'parent_id' => $this->input('parent_id')]);
    }

    public function getThread($id)
    {
        if ($this->input('viewed')) {
            $this->service->viewThread($id);
        }

        if ($this->input('edit')) {
            $thread = $this->service->getThreadForEdit($id);
        } else {
            $thread = $this->service->getThread($id, $this->input('page', 1), $this->input('perPage', 10), $this->input('sort', 'time'));
        }

        return $this->data($thread->toArray())->respond();
    }

    public function postThread()
    {
        return $this->data($this->service->createThread($this->input('category_id'), $this->input('title'), $this->input('post'))->toArray())->statusCreated()->respond();
    }

    public function putThread($id)
    {
        if (!is_null($this->input('closed'))) {
            $this->service->threadClosed($id, $this->input('closed'));
        } else if (!is_null($this->input('move'))) {
            $this->service->moveThread($id, $this->input('move'));
        } else if (!is_null($this->input('sticky'))) {
            $this->service->stickyThread($id, $this->input('sticky'));
        } else {
            $this->service->editThread($id, $this->input('title'), $this->input('first_post')['content']);
        }
    }

    public function deleteThread($id)
    {
        $this->service->deleteThread($id);
    }

    public function getPost($id)
    {
        return $this->data($this->service->getPostEdit($id)->toArray())->respond();
    }

    public function postPost()
    {
        return $this->data($this->service->createPost($this->input('thread_id'), $this->input('content'), $this->input('subscribe'))->toArray())->respond();
    }

    public function putPost($id)
    {
        $this->service->editPost($id, $this->input('content'));
    }

    public function deletePost($id)
    {
        $this->service->deletePost($id);
    }

    public function postReport()
    {
        // todo create ticket
    }

    public function postVote()
    {
        return $this->data($this->service->votePost($this->input('post_id'), $this->input('rating'))->toArray())->respond();
    }

    public function putSubscribe($threadId)
    {
        if ($this->input('cancel')) {
            $this->service->unsubscribe($threadId, \Auth::user()->userId());
        } else {
            $this->service->subscribe($threadId, \Auth::user()->userId());
        }
    }
} 