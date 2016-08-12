<?php namespace Biffy\Entities\File;

use Biffy\Entities\AbstractEntity;
use Biffy\Facades\StoreConfig;

class File extends AbstractEntity
{
    protected $fillable = [
        'path',
        'file_set_id',
    ];

    protected $appends = ['file_path', 'size'];

    protected $hidden = ['created_at', 'updated_at'];

    public function files() {
        return $this->hasMany('Biffy\Entities\File\File');
    }

    public function getFilePathAttribute()
    {
        return config('info.filemanagement.uploadDirectory') . '/' . $this->id . '-' .$this->path;
    }

    public function getSizeAttribute()
    {
        if( is_file(public_path() . '/' . $this->file_path) )
        {
            return round(filesize(public_path() . '/' . $this->file_path) / 1024, 1) . 'KB';
        }
        return 'NA';
    }

}
