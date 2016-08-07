<?php


namespace JeroenNoten\LaravelNewsletter\Mailgun;


use Illuminate\Contracts\Routing\UrlRoutable;

class Member implements UrlRoutable
{
    public $address;
    public $name;
    public $subscribed;

    public function __construct($data)
    {
        $this->address = $data->address;
        $this->name = $data->name;
        $this->subscribed = $data->subscribed;
    }

    public function getRouteKey()
    {
        return $this->address;
    }

    public function getRouteKeyName()
    {
    }
}