<?php

use JeroenNoten\LaravelNewsletter\Mailgun\Member;

class MemberTest extends PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $member = new Member('jeroennoten@me.com', 'Jeroen Noten', false);

        $this->assertEquals('jeroennoten@me.com', $member->address);
        $this->assertEquals('Jeroen Noten', $member->name);
        $this->assertEquals(false, $member->subscribed);
    }
}
