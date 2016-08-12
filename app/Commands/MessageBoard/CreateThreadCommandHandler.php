<?php namespace Biffy\Commands\MessageBoard;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\BoardPost\BoardPostRepositoryInterface;
use Biffy\Entities\BoardThread\BoardThreadRepositoryInterface;
use Biffy\Entities\User\UserRepositoryInterface;
use Biffy\Events\UserWasTagged;

class CreateThreadCommandHandler implements CommandHandler
{
    /**
     * @var BoardPostRepositoryInterface
     */
    private $boardPostRepository;

    /**
     * @var BoardThreadRepositoryInterface
     */
    private $boardThreadRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    function __construct(BoardPostRepositoryInterface $boardPostRepository, BoardThreadRepositoryInterface $boardThreadRepository, UserRepositoryInterface $userRepository)
    {
        $this->boardPostRepository = $boardPostRepository;
        $this->boardThreadRepository = $boardThreadRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws \Biffy\Services\Entities\CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $post = $this->boardPostRepository->create(['content' => $command->content, 'thread_id' => null, 'user_id' => null]);
        $command->thread = $this->boardThreadRepository->create(['title' => $command->title, 'first_post_id' => $post->id, 'category_id' => $command->categoryId, 'user_id' => $command->userId, 'latest_post' => $post->created_at]);

        $user = $this->userRepository->find($command->userId);
        $user->rep += config('info.message-boards.rep-per-post');
        $user->save();

        $command->thread->subscriptions()->attach($user, ['notify' => false]);

        if (count($command->tags)) {
            event(new UserWasTagged($user, $command->thread));
        }
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws \Biffy\Services\Entities\RollbackFailedException
     */
    public function handleRollback(AbstractCommand $command)
    {
        // TODO: Implement handleRollback() method.
    }
}