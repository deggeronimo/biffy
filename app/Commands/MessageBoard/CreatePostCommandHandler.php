<?php namespace Biffy\Commands\MessageBoard;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\BoardPost\BoardPostRepositoryInterface;
use Biffy\Entities\BoardThread\BoardThreadRepositoryInterface;
use Biffy\Entities\User\UserRepositoryInterface;
use Biffy\Events\ThreadHasNewPost;
use Biffy\Events\UserWasTagged;

class CreatePostCommandHandler implements CommandHandler
{
    /**
     * @var BoardPostRepositoryInterface
     */
    private $boardPostRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var BoardThreadRepositoryInterface
     */
    private $boardThreadRepository;

    public function __construct(BoardPostRepositoryInterface $boardPostRepositoryInterface, UserRepositoryInterface $userRepositoryInterface, BoardThreadRepositoryInterface $boardThreadRepositoryInterface)
    {
        $this->boardPostRepository = $boardPostRepositoryInterface;
        $this->userRepository = $userRepositoryInterface;
        $this->boardThreadRepository = $boardThreadRepositoryInterface;
    }

    /**
     * @param AbstractCommand $command
     *
     * @throws \Biffy\Services\Entities\CommandFailedException
     */
    public function handle(AbstractCommand $command)
    {
        $command->post = $this->boardPostRepository->create(['thread_id' => $command->threadId, 'content' => $command->content, 'user_id' => $command->userId]);

        $thread = $this->boardThreadRepository->find($command->threadId);
        $thread->latest_post = $command->post->created_at;
        $thread->save();

        $user = $this->userRepository->find($command->userId);
        $user->rep += config('info.message-boards.rep-per-post');
        $user->save();

        if ($command->subscribe) {
            /** @var $thread \Biffy\Entities\BoardThread\BoardThread */
            $thread->subscriptions()->sync([$user->id], false);
        }

        if (count($command->tags)) {
            event(new UserWasTagged($user, $thread, $command->post));
        }

        event(new ThreadHasNewPost($thread, \Auth::user()->userId()));
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