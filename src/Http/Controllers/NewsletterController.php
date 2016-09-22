<?php namespace JeroenNoten\LaravelNewsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;

class NewsletterController extends Controller
{
    public function subscribe(Request $request, MailgunInterface $mailgun)
    {
        $list = config('newsletter.list');
        $listId = head(explode('@', $list));
        $mailgun->addMember($listId, $request->input('email'), '');
    }
}
