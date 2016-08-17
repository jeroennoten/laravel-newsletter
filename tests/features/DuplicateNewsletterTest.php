<?php


use JeroenNoten\LaravelNewsletter\Mailgun\Mailgun;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;
use JeroenNoten\LaravelNewsletter\Mailgun\MailingList;
use JeroenNoten\LaravelNewsletter\Models\Newsletter;

class DuplicateNewsletterTest extends TestCase
{
    public function testDuplicate()
    {
        $this->mockMailgun();

        $newsletter = factory(Newsletter::class)->create(['list_id' => 'mylist']);

        $this->login();
        $this->visit("admin/newsletters/$newsletter->id");
        $this->submitForm('Dupliceren');

        $newId = $newsletter->id + 1;

        $this->seePageIs("admin/newsletters/$newId");
        $this->seeInDatabase('newsletters', [
            'id' => $newId,
            'subject' => $newsletter->subject,
            'body' => $newsletter->body,
            'list_id' => $newsletter->listId,
            'sent_at' => null,
        ]);
    }
}