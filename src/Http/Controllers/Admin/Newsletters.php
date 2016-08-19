<?php


namespace JeroenNoten\LaravelNewsletter\Http\Controllers\Admin;


use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;
use JeroenNoten\LaravelNewsletter\Models\Newsletter;
use JeroenNoten\LaravelNewsletter\Models\NewsletterFactory;

class Newsletters extends Controller
{
    private $mailgun;

    private $redirector;

    private $mailer;

    public function __construct(
        MailgunInterface $mailgun,
        Redirector $redirector,
        Mailer $mailer
    ) {
        $this->middleware('auth');
        $this->mailgun = $mailgun;
        $this->redirector = $redirector;
        $this->mailer = $mailer;
    }

    public function index()
    {
        $newsletters = Newsletter::all()->map(function (Newsletter $newsletter) {
            $newsletter->list = $this->mailgun->getList($newsletter->listId);
            return $newsletter;
        });
        return view('newsletter::admin.index', compact('newsletters'));
    }

    public function create(NewsletterFactory $factory)
    {
        return $this->show($factory->make());
    }

    public function show(Newsletter $newsletter)
    {
        if ($newsletter->isSent()) {
            $newsletter->list = $this->mailgun->getList($newsletter->listId);
            return view('newsletter::admin.show', compact('newsletter'));
        }
        $new = !$newsletter->exists;
        $lists = $this->mailgun->lists();
        return view('newsletter::admin.edit', compact('newsletter', 'new', 'lists'));
    }

    public function body(Newsletter $newsletter)
    {
        return view('newsletter::mails.newsletter', compact('newsletter'));
    }

    public function store(Request $request)
    {
        $newsletter = Newsletter::create($request->all());
        return $this->redirect($request->input('redirect') == 'edit' ? $newsletter : null);
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

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return $this->redirect();
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
            return $this->redirector->route('admin.newsletters.show', $newsletter);
        }
        return $this->redirector->route('admin.newsletters.index');
    }

    private function sendNewsletter(Newsletter $newsletter)
    {
        $view = 'newsletter::mails.newsletter';
        $this->mailer->send($view, compact('newsletter'), function (Message $message) use ($newsletter) {
            $list = $this->mailgun->getList($newsletter->listId);
            $message->to($list->address);
            $message->subject($newsletter->subject);
        });

        $newsletter->updateSentAt();
        $newsletter->save();

        return $this->redirect($newsletter);
    }
}