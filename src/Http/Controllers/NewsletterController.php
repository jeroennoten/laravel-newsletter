<?php namespace JeroenNoten\LaravelNewsletter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mailgun\Mailgun;

class NewsletterController extends Controller
{
    public function subscribe(Request $request, Mailgun $mailgun)
    {
        $list = config('newsletter.list');
        $mailgun->post("lists/$list/members", [
            'address' => $request->input('email'),
            'subscribed' => 'yes',
            'upsert' => 'yes'
        ]);
    }
}