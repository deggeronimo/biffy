<?php namespace Biffy\Entities\BoardThread;

use Biffy\Entities\AbstractRepositoryInterface;

interface BoardThreadRepositoryInterface extends AbstractRepositoryInterface
{
    public function getThread($id, $page, $perPage, $sort);
    public function getThreadForEdit($id);
    public function getByFirstPost($postId);
    public function countInCategory($categoryId);
    public function getLatestUnread($userId, $count);
} 