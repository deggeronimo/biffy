<?php namespace Biffy\Commands\MessageBoard;

use Biffy\Commands\AbstractCommand;
use Biffy\Commands\CommandHandler;
use Biffy\Entities\BoardPost\BoardPostRepositoryInterface;
use Biffy\Entities\BoardPostRep\BoardPostRepRepositoryInterface;
use Biffy\Entities\BoardThread\BoardThreadRepositoryInterface;
use Biffy\Entities\User\UserRepositoryInterface;
use Biffy\Services\Entities\CommandFailedException;

class VotePostCommandHandler implements CommandHandler
{
    /**
     * @var BoardPostRepositoryInterface
     */
    private $boardPostRepository;

    /**
     * @var BoardPostRepRepositoryInterface
     */
    private $boardPostRepRepository;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var BoardThreadRepositoryInterface
     */
    private $boardThreadRepository;

    public function __construct(
        BoardPostRepositoryInterface $boardPostRepositoryInterface,
        BoardPostRepRepositoryInterface $boardPostRepRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface,
        BoardThreadRepositoryInterface $boardThreadRepositoryInterface
    ) {
        $this->boardPostRepository = $boardPostRepositoryInterface;
        $this->boardPostRepRepository = $boardPostRepRepositoryInterface;
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
        $post = $this->boardPostRepository->find($command->postId);

        if (is_null($post->user_id)) {
            $thread = $this->boardThreadRepository->getByFirstPost($command->postId);
            $post->user_id = $thread->user_id;
        }

        if ($post->user_id == $command->votingUserId) {
            throw new CommandFailedException('board_err_vote_own_post');
        }

        if ($this->boardPostRepRepository->alreadyVoted($command->postId, $command->votingUserId)) {
            throw new CommandFailedException('board_err_already_voted');
        }

        // add board_rep entry
        $this->boardPostRepRepository->create(['user_id' => $command->votingUserId, 'post_id' => $command->postId, 'rating' => $command->rating]);

        // adjust post.rep
        $post->rep += $command->rating;
        $post->save();

        // adjust user.rep for voter and owner of post
        $votingUser = $this->userRepository->find($command->votingUserId);
        $votingUser->rep += config('info.message-boards.rep-for-voting');
        $votingUser->save();

        if ($command->rating > 0) {
            $voteType = 'upvote';
        } else {
            $voteType = 'downvote';
        }

        $postOwner = $this->userRepository->find($post->user_id);
        $postOwner->rep += config('info.message-boards.rep-per-' . $voteType);
        $postOwner->save();
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