<?php


namespace JeroenNoten\LaravelNewsletter\Mailgun;


use Illuminate\Contracts\Routing\UrlRoutable;

class Member implements UrlRoutable
{
    public $address;
    public $name;
    public $subscribed;

    public function __construct(string $address, string $name = null, bool $subscribed = true)
    {
        $this->address = $address;
        $this->name = $name;
        $this->subscribed = $subscribed;
    }

    public function getRouteKey()
    {
        return $this->address;
    }

    public function getRouteKeyName()
    {
    }
}
