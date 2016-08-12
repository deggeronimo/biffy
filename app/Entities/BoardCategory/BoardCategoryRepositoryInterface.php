<?php namespace Biffy\Entities\BoardCategory;

use Biffy\Entities\AbstractRepositoryInterface;

interface BoardCategoryRepositoryInterface extends AbstractRepositoryInterface
{
    public function categoryTree();
    public function getCategories();
    public function getCategory($id);
    public function addCategory($parentId, $data);
    public function updateCategory($id, $data);
    public function getBoard($id, $page, $perPage, $sort);
} 