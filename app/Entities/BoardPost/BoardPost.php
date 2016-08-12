<?php namespace Biffy\Entities\BoardPost;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\BoardPostRep\BoardPostRep;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardPost extends AbstractEntity
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'thread_id',
        'content',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }

    public function thread()
    {
        return $this->belongsTo('Biffy\Entities\BoardThread\BoardThread');
    }

    public function rep_votes()
    {
        return $this->hasMany('Biffy\Entities\BoardPostRep\BoardPostRep', 'post_id');
    }
}