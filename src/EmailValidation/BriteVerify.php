<?php

namespace JeroenNoten\LaravelNewsletter\EmailValidation;

use GuzzleHttp\Client;

class BriteVerify
{
    const URI = 'https://bpi.briteverify.com/emails.json';
    private $client;
    private $apiKey;

    public function __construct(Client $client, $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    public function verify($email)
    {
        return json_decode($this->client->get(self::URI, ['query' => [
            'address' => $email,
            'apikey' => $this->apiKey
        ]])->getBody());
    }
}