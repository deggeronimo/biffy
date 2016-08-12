<?php namespace Biffy\Entities\SupportTicket;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\SupportTicketUpdate\SupportTicketUpdate;
use Illuminate\Support\Facades\Auth;

class SupportTicket extends AbstractEntity
{
    protected $fillable = [
        'title',
        'author_id',
        'user_id',
        'status_id',
        'category_id'
    ];

    protected $appends = [
        'author_name',
        'watcher_ids',
        'watchers',
        'category_name',
        'status_name',
        'assignee_name',
        'messages',
    ];

    protected $hidden = ['author', 'assignee', 'status', 'category'];

    public static function boot()
    {
        parent::boot();

        static::creating(function(SupportTicket $supportTicket)
        {
            //Authenticated non-cli operations will override author_id based on logged on user
            if(php_sapi_name() !== 'cli' && Auth::check()) {
                //@todo As per laravel docs `$id = Auth::id();` but this returns null. Laravel authentication relies on session data for retrieving this id.
                $supportTicket->attributes['author_id'] = Auth::user()->userId(); //we cannot use $supportTicket->user_id as this is a readonly attribute
            }
        });
    }

    /*/
    / Relationships
    /*/

    public function author()
    {
        return $this->belongsTo('Biffy\Entities\User\User', 'author_id');
    }

    public function assignee()
    {
        return $this->belongsTo('Biffy\Entities\User\User', 'user_id');
    }

    public function watchers() {
        return $this->belongsToMany('Biffy\Entities\User\User', 'support_ticket_watcher', 'support_ticket_id', 'user_id');
    }

    public function status()
    {
        return $this->belongsTo('Biffy\Entities\SupportTicketStatus\SupportTicketStatus', 'status_id');
    }

    public function category()
    {
        return $this->belongsTo('Biffy\Entities\SupportTicketCategory\SupportTicketCategory', 'category_id');
    }

    public function updates() {
        return $this->hasMany('Biffy\Entities\SupportTicketUpdate\SupportTicketUpdate');
    }

    /*/
    / Smart attributes for dealing with relationship fields
    /*/

    public function getAuthorNameAttribute()
    {
        return $this->author->name;
    }

    public function getAssigneeNameAttribute()
    {
        return $this->assignee ? $this->assignee->name : 'none';
    }

    public function setWatcherIdsAttribute($ids)
    {
        $this->watchers()->sync( is_array($ids) ? $ids : [] );
    }

    public function getWatcherIdsAttribute()
    {
        return $this->watchers()->lists('user_id');
    }

    public function getWatchersAttribute()
    {
        return $this->watchers()->get()->toArray();
    }

    // PS: updates are not part of appends
    public function getUpdatesAttribute()
    {
        return $this->updates()->get()->toArray();
    }

    public function getStatusNameAttribute()
    {
        return $this->status ? $this->status->name : 'none';
    }

    public function getCategoryNameAttribute()
    {
        return $this->category ? $this->category->name : 'none';
    }

    public function getMessagesAttribute() {
        return SupportTicketUpdate::where('support_ticket_id', $this->id)->whereNotNull('message')->count();
    }

    public function syncIndex() {
        $assigneeUpdate = SupportTicketUpdate::where('support_ticket_id', $this->id)
            ->whereNotNull('user_id')
            ->orderBy('created_at', 'desc')
            ->first();
        $this->user_id = $assigneeUpdate ? $assigneeUpdate->user_id : null;

        $statusUpdate = SupportTicketUpdate::where('support_ticket_id', $this->id)
            ->whereNotNull('status_id')
            ->orderBy('created_at', 'desc')
            ->first();
        $this->status_id = $statusUpdate ? $statusUpdate->status_id : null;
        return $this;
    }

}
