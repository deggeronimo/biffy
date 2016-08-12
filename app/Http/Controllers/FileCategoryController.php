<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\FileCategory\FileCategoryRepositoryInterface;

/**
 * Class FileCategoryController
 * @package Biffy\Http\Controllers
 */
class FileCategoryController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var FileCategoryRepositoryInterface
     */
    public $repo;

    /**
     * @param FileCategoryRepositoryInterface $repo
     */
    function __construct(FileCategoryRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}
