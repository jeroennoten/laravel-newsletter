<?php


use JeroenNoten\LaravelNewsletter\Models\Newsletter;

class DefaultMailingListTest extends TestCase
{
    public function testSetDefault()
    {
        @unlink(storage_path('newsletter/settings/default_list.txt'));

        $this->mockMailgun();

        $this->login();
        $this->visit('admin/newsletter-lists');

        $this->submitForm('setDefaultMailingList456Button');
        $this->seePageIs('admin/newsletter-lists');

        $this->visit('admin/newsletters/create');

        /** @var Newsletter $newsletter */
        $newsletter = $this->response->original->getData()['newsletter'];
        $this->assertEquals('456', $newsletter->listId);
    }
}