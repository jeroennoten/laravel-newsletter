<?php namespace JeroenNoten\LaravelNewsletter\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use JeroenNoten\LaravelNewsletter\Mailgun\MailingList;

/**
 * @property mixed subject
 * @property mixed listId
 * @property MailingList list
 * @property mixed id
 */
class Newsletter extends Model
{
    protected $dates = ['sent_at'];

    protected $fillable = ['subject', 'body', 'list_id'];

    public function setListIdAttribute($value)
    {
        $this->attributes['list_id'] = $value;
    }

    public function getListIdAttribute()
    {
        return $this->attributes['list_id'] ?? null;
    }

    public function isSent(): bool
    {
        if ($this->getAttribute('sent_at')) {
            return true;
        }
        return false;
    }

    public function updateSentAt()
    {
        $this->setAttribute('sent_at', Carbon::now());
    }
}