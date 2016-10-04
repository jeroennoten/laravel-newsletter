<?php


namespace JeroenNoten\LaravelNewsletter\Mailgun;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Mailgun\Connection\Exceptions\MissingEndpoint;
use Mailgun\Mailgun as BaseMailgun;

class Mailgun implements MailgunInterface
{
    private $mailgun;
    private $domain;

    public function __construct(BaseMailgun $mailgun, $domain)
    {
        $this->mailgun = $mailgun;
        $this->domain = $domain;
    }

    public function lists()
    {
        return $this->getAll('lists')->map(function ($item) {
            return new MailingList($item);
        })->filter(function (MailingList $list) {
            return $list->isDomain($this->domain);
        });
    }

    public function getList($listId)
    {
        try {
            $data = $this->get($this->listPath($listId));
            return new MailingList($data->list);
        } catch (MissingEndpoint $e) {
            return null;
        }
    }

    public function addList($name, $description)
    {
        $data = $this->post('lists', [
            'address' => $this->generateAddress(),
            'name' => $name,
            'description' => $description
        ])->list;

        return new MailingList($data);
    }

    public function updateList($listId, $name, $description)
    {
        $data = $this->put($this->listPath($listId), [
            'name' => $name,
            'description' => $description
        ])->list;

        return new MailingList($data);
    }

    public function deleteList($listId)
    {
        $this->delete($this->listPath($listId));
    }

    public function members($listId, $perPage = 20)
    {
        return $this->getPaginated($this->listPath($listId, 'members'), $perPage, function ($item) {
            return new Member($item->address, $item->name, $item->subscribed);
        });
    }

    public function allMembers($listId): Collection
    {
        return $this->getAll($this->listPath($listId, 'members'))->map(function ($item) {
            return new Member($item->address, $item->name, $item->subscribed);
        });
    }

    public function addMember($listId, $address, $name)
    {
        $this->post($this->listPath($listId, 'members'), [
            'address' => $address,
            'name' => $name,
            'subscribed' => 'yes',
            'upsert' => 'yes',
        ]);
    }

    public function deleteMember($listId, $memberAddress)
    {
        $this->delete($this->listPath($listId, "members/$memberAddress"));
    }

    private function get($path, $query = [])
    {
        return $this->mailgun->get($path, $query)->http_response_body;
    }

    private function post($path, $data)
    {
        return $this->mailgun->post($path, $data)->http_response_body;
    }

    private function put($path, $data)
    {
        return $this->mailgun->put($path, $data)->http_response_body;
    }

    private function delete($path)
    {
        return $this->mailgun->delete($path)->http_response_body;
    }

    private function getAll($path)
    {
        $all = new Collection;
        do {
            $response = $this->get($path, ['skip' => $all->count()]);
            $items = $response->items;
            $all = $all->merge($items);
        } while ($items);
        return $all;
    }

    private function getPaginated($path, $perPage = 100, callable $transformer = null)
    {
        $page = Paginator::resolveCurrentPage();

        $response = $this->get($path, [
            'limit' => $perPage,
            'skip' => ($page - 1) * $perPage
        ]);

        $items = collect($response->items);
        $total = $response->total_count;

        if ($transformer) {
            $items->transform($transformer);
        }

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => Paginator::resolveCurrentPath()]
        );
    }

    private function listPath($listId, $path = null)
    {
        return "lists/$listId@$this->domain" . ($path ? "/$path" : '');
    }

    private function generateAddress()
    {
        $listId = uniqid();
        return "$listId@$this->domain";
    }
}
