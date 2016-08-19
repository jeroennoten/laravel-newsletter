<?php


namespace JeroenNoten\LaravelNewsletter\Models;


use JeroenNoten\LaravelNewsletter\MailingLists\Manager;

class NewsletterFactory
{
    private $listManager;

    public function __construct(Manager $listManager)
    {
        $this->listManager = $listManager;
    }

    public function make()
    {
        $newsletter = new Newsletter;
        $newsletter->listId = $this->listManager->getDefaultId();
        return $newsletter;
    }
}