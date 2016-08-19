<?php

namespace JeroenNoten\LaravelNewsletter\EmailValidation;

use GuzzleHttp\Client;
use JeroenNoten\LaravelNewsletter\Counters\BriteVerifyCounter;

class BriteVerify
{
    const URI = 'https://bpi.briteverify.com/emails.json';
    private $client;
    private $apiKey;
    private $counter;

    public function __construct(Client $client, $apiKey, BriteVerifyCounter $counter)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
        $this->counter = $counter;
    }

    public function verify($email)
    {
        $this->counter->add(1);
        return json_decode($this->client->get(self::URI, ['query' => [
            'address' => $email,
            'apikey' => $this->apiKey
        ]])->getBody());
    }
}