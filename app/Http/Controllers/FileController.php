<?php namespace Biffy\Http\Controllers;

use Biffy\Entities\File\FileRepositoryInterface;

/**
 * Class FileController
 * @package Biffy\Http\Controllers
 */
class FileController extends ApiController
{
    use Helpers\CRUDControllerHelper;

    /**
     * @var FileRepositoryInterface
     */
    public $repo;

    /**
     * @param FileRepositoryInterface $repo
     */
    function __construct(FileRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function postUpload()
    {
        $file = \Input::file('file');
        $userId = \Auth::user()->userId();
        $dir = config('info.filemanagement.uploadDirectory');
        $timestamp = \Time::fileTimestamp();
        $destination = $dir . '/' . $userId;
        $filename = $timestamp . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return $this->data(['location' => asset($destination . '/' . $filename)])->respond();
    }

}
