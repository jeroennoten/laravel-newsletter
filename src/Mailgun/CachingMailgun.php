<?php


namespace JeroenNoten\LaravelNewsletter\Mailgun;


use Illuminate\Cache\Repository;

class CachingMailgun implements MailgunInterface
{
    private $mailgun;

    private $cache;

    public function __construct(Mailgun $mailgun, Repository $cache)
    {
        $this->mailgun = $mailgun;
        $this->cache = $cache;
    }

    public function lists()
    {
        return $this->cache->rememberForever('newsletter::lists', function () {
            return $this->mailgun->lists();
        });
    }

    public function list($listId)
    {
        return $this->cache->rememberForever("newsletter::lists.$listId", function () use ($listId) {
            return $this->mailgun->list($listId);
        });
    }

    public function addList($name, $description)
    {
        $list = $this->mailgun->addList($name, $description);
        $listId = $list->getId();

        $this->cache->forget('newsletter::lists');
        $this->cache->forget("newsletter::lists.$listId");

        return $list;
    }

    public function updateList($listId, $name, $description)
    {
        $list = $this->mailgun->updateList($listId, $name, $description);

        $this->cache->forget('newsletter::lists');
        $this->cache->forget("newsletter::lists.$listId");

        return $list;
    }

    public function deleteList($listId)
    {
        $this->mailgun->deleteList($listId);

        $this->cache->forget('newsletter::lists');
        $this->cache->forget("newsletter::lists.$listId");
    }

    public function members($listId, $perPage = 20)
    {
        return $this->mailgun->members($listId, $perPage);
    }

    public function addMember($listId, $address, $name)
    {
        $this->cache->forget('newsletter::lists');
        $this->cache->forget("newsletter::lists.$listId");
        return $this->mailgun->addMember($listId, $address, $name);
    }

    public function deleteMember($listId, $memberAddress)
    {
        $this->cache->forget('newsletter::lists');
        $this->cache->forget("newsletter::lists.$listId");
        return $this->mailgun->deleteMember($listId, $memberAddress);
    }
}