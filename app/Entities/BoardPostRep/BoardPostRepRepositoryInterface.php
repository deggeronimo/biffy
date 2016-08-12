<?php namespace Biffy\Entities\BoardPostRep;

use Biffy\Entities\AbstractRepositoryInterface;

interface BoardPostRepRepositoryInterface extends AbstractRepositoryInterface
{
    public function alreadyVoted($postId, $userId);
}