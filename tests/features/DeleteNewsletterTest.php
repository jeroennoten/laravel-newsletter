<?php


use JeroenNoten\LaravelNewsletter\Models\Newsletter;

class DeleteNewsletterTest extends TestCase
{

    public function testDelete()
    {
        $this->mockMailgun();

        /** @var Newsletter $newsletter */
        $newsletter = factory(Newsletter::class)->create();

        $this->seeInDatabase('newsletters', $newsletter->getAttributes());

        $this->login();
        $this->visit('admin/newsletters');
        $this->submitForm("deleteNewsletter{$newsletter->id}Button");

        $this->dontSeeInDatabase('newsletters', $newsletter->getAttributes());
    }

}