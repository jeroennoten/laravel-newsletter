<?php


namespace JeroenNoten\LaravelNewsletter\Http\Controllers\Admin;


use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use JeroenNoten\LaravelNewsletter\Mailgun\MailgunInterface;

class Lists extends Controller
{
    use ValidatesRequests;

    private $mailgun;

    private $view;

    private $redirector;

    public function __construct(MailgunInterface $mailgun, Factory $view, Redirector $redirector)
    {
        $this->middleware('auth');
        $this->mailgun = $mailgun;
        $this->view = $view;
        $this->redirector = $redirector;
    }

    public function index()
    {
        $lists = $this->mailgun->lists();
        return $this->view->make('newsletter::admin.lists.index', compact('lists'));
    }

    public function show($listId)
    {
        $list = $this->mailgun->list($listId);
        $members = $this->mailgun->members($listId);
        return $this->view->make('newsletter::admin.lists.show', compact('list', 'members'));
    }

    public function create()
    {
        return view('newsletter::admin.lists.create');
    }

    public function store(Request $request)
    {
        $this->validateList($request);

        $list = $this->mailgun->addList($request->input('name'), $request->input('description'));

        return $this->redirector->route('admin.newsletters.lists.show', $list);
    }

    public function edit($listId)
    {
        $list = $this->mailgun->list($listId);
        return $this->view->make('newsletter::admin.lists.edit', compact('list', 'members'));
    }

    public function update($listId, Request $request)
    {
        $this->validateList($request);

        $list = $this->mailgun->updateList($listId, $request->input('name'), $request->input('description'));

        return $this->redirector->route('admin.newsletters.lists.show', $list);
    }

    public function destroy($listId)
    {
        $this->mailgun->deleteList($listId);

        return $this->redirector->route('admin.newsletters.lists.index');
    }

    private function validateList(Request $request)
    {
        $this->validate($request, [
            'name' => 'string|required',
        ], [
            'required' => 'Dit veld is vereist.'
        ]);
    }
}