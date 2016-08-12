<?php namespace Biffy\Entities\BoardPost;

use Biffy\Entities\AbstractRepositoryInterface;

interface BoardPostRepositoryInterface extends AbstractRepositoryInterface
{
    public function getPostEdit($id);
    public function getPost($id);
    public function countInThread($threadId);
}