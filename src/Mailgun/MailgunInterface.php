<?php
namespace JeroenNoten\LaravelNewsletter\Mailgun;

interface MailgunInterface
{
    public function lists();

    public function getList($listId);

    public function addList($name, $description);

    public function updateList($listId, $name, $description);

    public function deleteList($listId);

    public function members($listId, $perPage = 20);

    public function addMember($listId, $address, $name);

    public function deleteMember($listId, $memberAddress);
}