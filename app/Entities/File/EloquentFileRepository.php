<?php namespace Biffy\Entities\File;

use Biffy\Entities\EloquentAbstractChildRepository;
use Biffy\Entities\FileSet\FileSet;

/**
 * Class EloquentFileRepository
 * @package Biffy\Entities\File
 */
class EloquentFileRepository extends EloquentAbstractChildRepository implements FileRepositoryInterface
{
    /**
     * @var array
     */
    protected $sorters = [
        'name' => [],
    ];

    /**
     * @var array
     */
    protected $filters = [
        'name' => ['name LIKE ?', '%:value%'],
        'search' => ['name LIKE ?', '%:value%'],
    ];


    public function __construct(File $model, FileSet $parent)
    {
        $this->model = $model;
        $this->parent = $parent;
        $this->childRelation = 'files';
    }

    public function create($attributes)
    {
        $upload = \Input::file('upload');

        $file = $this->model->create([
            'file_set_id' => $attributes['file_set_id'],
            'path' => $upload->getClientOriginalName()
        ]);
        $destinationName = $file->id . '-' . $upload->getClientOriginalName();

        $upload->move(config('info.filemanagement.uploadDirectory'), $destinationName);

        return $file;
    }

    public function delete($id)
    {
        $file_path = File::findOrFail($id)->file_path;

        if( is_file(public_path() . '/' . $file_path) ) unlink(public_path() . '/' . $file_path);

        return $this->model->destroy($id);
    }

}
