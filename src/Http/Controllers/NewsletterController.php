<?php namespace JeroenNoten\LaravelNewsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JeroenNoten\LaravelNewsletter\Mailgun\Mailgun;

class NewsletterController extends Controller
{
    public function subscribe(Request $request, Mailgun $mailgun)
    {
        $list = config('newsletter.list');
        $listId = last(explode('@', $list));
        $mailgun->addMember($listId, $request->input('email'), '');
    }
}
