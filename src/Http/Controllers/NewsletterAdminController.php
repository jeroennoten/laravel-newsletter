<?php


namespace JeroenNoten\LaravelNewsletter\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use JeroenNoten\LaravelNewsletter\Newsletter;
use Mail;
use Mailgun\Mailgun;
use Redirect;

class NewsletterAdminController extends Controller
{
    private $mailgun;

    public function __construct(Mailgun $mailgun)
    {
        $this->middleware('auth');
        $this->mailgun = $mailgun;
    }

    public function index()
    {
        $emails = $this->getEmails();
        $newsletters = Newsletter::all();
        return view('newsletter::admin.index', compact('newsletters', 'emails'));
    }

    public function create()
    {
        return $this->show(new Newsletter);
    }

    public function show(Newsletter $newsletter)
    {
        if ($newsletter->isSent()) {
            return view('newsletter::admin.show', compact('newsletter'));
        }
        $new = !$newsletter->exists;
        return view('newsletter::admin.edit', compact('newsletter', 'new'));
    }

    public function store(Request $request)
    {
        Newsletter::create($request->all());
        return $this->redirect();
    }

    public function storeAndSend(Request $request)
    {
        $newsletter = Newsletter::create($request->all());
        $this->sendNewsletter($newsletter);
        return $this->redirect($newsletter);
    }

    public function update(Newsletter $newsletter, Request $request)
    {
        $this->updateNewsletter($newsletter, $request);
        return $this->redirect();
    }

    public function updateAndSend(Newsletter $newsletter, Request $request)
    {
        $this->updateNewsletter($newsletter, $request);
        $this->sendNewsletter($newsletter);
        return $this->redirect($newsletter);
    }

    public function preview(Request $request)
    {
        $newsletter = new Newsletter($request->all());
        return view('newsletter::mails.newsletter', compact('newsletter'));
    }

    private function updateNewsletter(Newsletter $newsletter, Request $request)
    {
        $newsletter->update($request->all());
    }

    private function redirect(Newsletter $newsletter = null)
    {
        if ($newsletter) {
            return Redirect::route('admin.newsletters.show', $newsletter);
        }
        return Redirect::route('admin.newsletters.index');
    }

    private function sendNewsletter(Newsletter $newsletter)
    {
        $view = 'newsletter::mails.newsletter';
        Mail::send($view, compact('newsletter'), function (Message $message) use ($newsletter) {
            $message->to(config('newsletter.list'));
            $message->subject($newsletter['subject']);
        });

        $newsletter->updateSentAt();
        $newsletter->save();

        return $this->redirect($newsletter);
    }

    private function getEmails()
    {
        $list = config('newsletter.list');
        $response = null;
        $members = [];
        do {
            $data = ['subscribed' => 'yes'];
            if ($response) {
                $data['address'] = last($response->http_response_body->items)->address;
                $data['page'] = 'next';
            }
            $response = $this->mailgun->get("lists/$list/members/pages", $data);
            $members = array_merge($members, $response->http_response_body->items);
        } while ($response->http_response_body->items);
        return collect($members)->map(function ($member) {
            return $member->address;
        })->all();
    }
}