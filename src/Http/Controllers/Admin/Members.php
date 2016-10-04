<?php

namespace JeroenNoten\LaravelNewsletter\Http\Controllers\Admin;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use JeroenNoten\LaravelNewsletter\EmailValidation\EmailValidator;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;

class Members extends Controller
{
    use ValidatesRequests;

    private $mailgun;

    private $view;

    /**
     * @var Redirector
     */
    private $redirector;

    /**
     * @var EmailValidator
     */
    private $emailValidator;

    public function __construct(
        MailgunInterface $mailgun,
        Factory $view,
        Redirector $redirector,
        EmailValidator $emailValidator
    ) {
        $this->middleware('auth');
        $this->mailgun = $mailgun;
        $this->view = $view;
        $this->redirector = $redirector;
        $this->emailValidator = $emailValidator;
    }

    public function index($listId)
    {
        return $this->mailgun->allMembers($listId);
    }

    public function store($listId, Request $request)
    {
        $email = $request->input('email');
        $name = $request->input('name');

        if (! $this->emailValidator->isValid($email)) {
            if ($request->wantsJson()) {
                return ['status' => 'invalid'];
            }

            return $this->redirector->back()->withInput()->withErrors(['Ongeldig e-mailadres']);
        }

        $this->mailgun->addMember($listId, $email, $name);

        if ($request->wantsJson()) {
            return ['status' => 'ok'];
        }

        return $this->redirectToList($listId);
    }

    public function destroy($listId, $memberAddress, Request $request)
    {
        $this->mailgun->deleteMember($listId, $memberAddress);

        if ($request->wantsJson()) {
            return ['status' => 'ok'];
        }

        return $this->redirectToList($listId);
    }

    private function redirectToList($listId)
    {
        return $this->redirector->route('admin.newsletters.lists.show', $listId);
    }
}
