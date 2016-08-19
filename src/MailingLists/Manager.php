<?php


namespace JeroenNoten\LaravelNewsletter\MailingLists;


use Illuminate\Foundation\Application;
use JeroenNoten\LaravelNewsletter\Mailgun\MailingList;

class Manager
{
    private $default;

    public function __construct(DefaultMailingListSetting $default)
    {
        $this->default = $default;
    }

    public function setDefaultId($listId)
    {
        $this->default->write($listId);
    }

    public function getDefaultId()
    {
        return $this->default->read();
    }
}