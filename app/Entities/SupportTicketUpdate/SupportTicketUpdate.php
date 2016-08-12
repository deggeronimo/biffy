<?php namespace Biffy\Entities\SupportTicketUpdate;

use Biffy\Entities\AbstractEntity;
use Biffy\Entities\SupportTicket\SupportTicket;
use Illuminate\Support\Facades\Auth;

class SupportTicketUpdate extends AbstractEntity
{
    protected $fillable = [
        'support_ticket_id',
        'author_id',
        'user_id',
        'status_id',
        'message',
    ];

    protected $appends = ['author_name', 'assignee_name', 'status_name'];

    protected $hidden = ['author', 'assignee', 'status'];

    public static function boot()
    {
        parent::boot();

        static::creating(function(SupportTicketUpdate $supportTicketUpdate)
        {
            //Authenticated non-cli operations will override author_id based on logged on user
            if(php_sapi_name() !== 'cli' && Auth::check()) {
                //@todo As per laravel docs `$id = Auth::id();` but this returns null. Laravel authentication relies on session data for retrieving this id.
                $supportTicketUpdate->attributes['author_id'] = Auth::user()->userId();
            }
        });

        static::created(function(SupportTicketUpdate $supportTicketUpdate) {
            SupportTicket::findOrFail($supportTicketUpdate->support_ticket_id)->syncIndex()->save();
        });

        static::updated(function(SupportTicketUpdate $supportTicketUpdate) {
            SupportTicket::findOrFail($supportTicketUpdate->support_ticket_id)->syncIndex()->save();
        });

        static::deleted(function(SupportTicketUpdate $supportTicketUpdate) {
            SupportTicket::findOrFail($supportTicketUpdate->support_ticket_id)->syncIndex()->save();
        });
    }

//    @todo This needs improvement for security purposes, same for SupportTicket
//    public function setAuthorIdAttribute($author_id)
//    {
//        if(php_sapi_name() !== 'cli') { //non-cli operations cannot set author_id directly
//            //@todo Add new ReadonlyAttributeException
//            throw new \BadMethodCallException('Cannot manually set author_id for support tickets');
//        } else {
//            $this->attributes['author_id'] = $author_id;
//        }
//    }

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

    public function status()
    {
        return $this->belongsTo('Biffy\Entities\SupportTicketStatus\SupportTicketStatus', 'status_id');
    }

    public function getAuthorNameAttribute()
    {
        return $this->author->name;
    }

    public function getAssigneeNameAttribute()
    {
        return $this->assignee ? $this->assignee->name : null;
    }

    public function getStatusNameAttribute()
    {
        return $this->status ? $this->status->name : null;
    }

}
