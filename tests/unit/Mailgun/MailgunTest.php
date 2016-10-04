<?php

use JeroenNoten\LaravelNewsletter\Mailgun\Mailgun;
use Mailgun\Mailgun as BaseMailgun;

class MailgunTest extends TestCase
{
    /** @var  Mailgun */
    private $mailgun;

    public function setUp()
    {
        parent::setUp();

        $this->mailgun = new Mailgun(new BaseMailgunStub, 'example.com');
    }

    public function testAllMembers()
    {
        $this->assertCount(3, $this->mailgun->allMembers('list'));
    }

    public function testMembers()
    {
        $this->assertCount(2, $this->mailgun->members('list', 2));
    }
}

class BaseMailgunStub extends BaseMailgun
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($endpointUrl, $queryString = [])
    {
        return [
            'lists/list@example.com/members' => (object) [
                'http_response_body' => (object) [
                    'total_count' => 3,
                    'items' => $queryString['skip'] == 0 ? [
                        (object) [
                            'address'    => 'jeroennoten@me.com',
                            'name'       => 'Jeroen Noten',
                            'subscribed' => true,
                        ],
                        (object) [
                            'address'    => 'lotte@me.com',
                            'name'       => 'Lotte Pijpers',
                            'subscribed' => false,
                        ],
                    ] : ($queryString['skip'] == 2 ? [
                        (object) [
                            'address'    => 'piet@example.com',
                            'name'       => 'Piet Snot',
                            'subscribed' => true,
                        ],
                    ] : []),
                ],
            ],
        ][$endpointUrl];
    }
}
