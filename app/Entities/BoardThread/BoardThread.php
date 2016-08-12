<?php namespace Biffy\Entities\BoardThread;

use Biffy\Entities\AbstractEntity;
use Illuminate\Database\Eloquent\SoftDeletes;

class BoardThread extends AbstractEntity
{
    use SoftDeletes;

    protected $dates = ['deleted_at', 'latest_post'];

    protected $fillable = [
        'title',
        'first_post_id',
        'category_id',
        'user_id',
        'closed',
        'sticky',
        'views',
        'latest_post'
    ];

    public function first_post()
    {
        return $this->hasOne('Biffy\Entities\BoardPost\BoardPost', 'id', 'first_post_id');
    }

    public function posts()
    {
        return $this->hasMany('Biffy\Entities\BoardPost\BoardPost', 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo('Biffy\Entities\User\User');
    }

    public function category()
    {
        return $this->belongsTo('Biffy\Entities\BoardCategory\BoardCategory');
    }

    public function subscriptions()
    {
        return $this->belongsToMany('Biffy\Entities\User\User', 'board_thread_subscriptions', 'thread_id', 'user_id')->withPivot('notify');
    }

    public function thread_views()
    {
        return $this->belongsToMany('Biffy\Entities\User\User', 'board_thread_views', 'thread_id', 'user_id')->withPivot('current');
    }
} 