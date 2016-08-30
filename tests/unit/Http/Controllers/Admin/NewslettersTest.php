<?php

use Illuminate\Auth\GenericUser;
use JeroenNoten\LaravelNewsletter\Http\Controllers\Admin\Newsletters;

class NewslettersTest extends TestCase
{
    public function testDelete()
    {
        $this->actingAs(new GenericUser([]));

        $newsletter = $this->createNewsletter();

        $this->action('delete', Newsletters::class . '@destroy', [$newsletter]);

        $this->assertNull($newsletter->fresh());
        $this->assertRedirectedToAction(Newsletters::class . '@index');
    }

}