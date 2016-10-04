<?php

use Illuminate\Support\Collection;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;
use JeroenNoten\LaravelNewsletter\Mailgun\Member;
use JeroenNoten\LaravelNewsletter\Mailgun\MemberListSearch;

class MemberListSearchTest extends TestCase
{
    /** @var  MemberListSearch */
    private $searchEngine;

    private $members;

    public function setUp()
    {
        parent::setUp();

        $this->members = [
            new Member('jeroennoten@me.com'),
            new Member('john@example.com'),
        ];
        $this->searchEngine = new MemberListSearch(new MailgunStub(['list' => $this->members]));
    }

    public function testReturnMember()
    {
        $results = $this->searchEngine->search('list', 'jeroennoten@me.com');

        $this->assertContains($this->members[0], $results);
    }

    public function testFullAddressSearch()
    {
        $results = $this->searchEngine->search('list', 'john@example.com');

        $this->assertInternalType('array', $results);
        $this->assertArraySubset($results, array_except($this->members, 1));
        $this->assertContains($this->members[1], $results);
    }
}

class MailgunStub implements MailgunInterface {

    private $lists;

    public function __construct(array $lists = [])
    {
        $this->lists = $lists;
    }

    public function lists()
    {
        // TODO: Implement lists() method.
    }

    public function getList($listId)
    {
        // TODO: Implement getList() method.
    }

    public function addList($name, $description)
    {
        // TODO: Implement addList() method.
    }

    public function updateList($listId, $name, $description)
    {
        // TODO: Implement updateList() method.
    }

    public function deleteList($listId)
    {
        // TODO: Implement deleteList() method.
    }

    public function members($listId, $perPage = 20)
    {
        // TODO: Implement members() method.
    }

    public function allMembers($listId): Collection
    {
        return new Collection($this->lists[$listId]);
    }

    public function addMember($listId, $address, $name)
    {
        // TODO: Implement addMember() method.
    }

    public function deleteMember($listId, $memberAddress)
    {
        // TODO: Implement deleteMember() method.
    }
}
