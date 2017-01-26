<?php


namespace JeroenNoten\LaravelNewsletter\EmailValidation;


use Illuminate\Contracts\Validation\Factory;

class EmailValidator
{
    private $briteVerify;

    private $validation;

    public function __construct(Factory $validation, BriteVerify $briteVerify)
    {
        $this->briteVerify = $briteVerify;
        $this->validation = $validation;
    }

    public function isValid($email)
    {
        $validation = $this->validation->make(compact('email'), [
            'email' => 'required|email'
        ]);
        if ($validation->fails()) {
            return false;
        }

        return in_array($this->briteVerify->verify($email)->status, ['valid', 'accept_all']);
    }
}