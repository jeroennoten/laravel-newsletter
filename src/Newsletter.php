<?php namespace JeroenNoten\LaravelNewsletter;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $dates = ['sent_at'];

    protected $fillable = ['subject', 'body'];

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