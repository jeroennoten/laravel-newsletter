<?php


namespace JeroenNoten\LaravelNewsletter\Mailgun;


use Illuminate\Contracts\Routing\UrlRoutable;

class MailingList implements UrlRoutable
{
    public $address;
    public $name;
    public $description;
    public $membersCount;

    public function __construct($data)
    {
        $this->name = $data->name;
        $this->description = $data->description;
        $this->membersCount = $data->members_count;
        $this->address = $data->address;
    }

    public function isDomain($domain)
    {
        return $this->getDomain() == $domain;
    }

    public function getId()
    {
        return head($this->getAddressParts());
    }

    public function getDomain()
    {
        return last($this->getAddressParts());
    }

    private function getAddressParts()
    {
        return explode('@', $this->address);
    }

    public function getRouteKey()
    {
        return $this->getId();
    }

    public function getRouteKeyName()
    {
    }
}