<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\FileSet\FileSetRepositoryInterface;

/**
 * Class FileSetController
 * @package Biffy\Http\Controllers
 */
class FileSetController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var FileSetRepositoryInterface
     */
    public $repo;

    /**
     * @param FileSetRepositoryInterface $repo
     */
    function __construct(FileSetRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

}
