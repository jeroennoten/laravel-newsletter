<?php


use Illuminate\Foundation\Testing\WithoutMiddleware;
use JeroenNoten\LaravelNewsletter\Http\Controllers\Admin\Newsletters;

class NewslettersTest extends TestCase
{
    use WithoutMiddleware;

    public function testDelete()
    {
        $newsletter = $this->createNewsletter();

        $this->action('delete', Newsletters::class . '@destroy', [$newsletter]);

        $this->assertNull($newsletter->fresh());
        $this->assertRedirectedToAction(Newsletters::class . '@index');
    }

}