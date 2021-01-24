<?php


namespace App\Exceptions;


use Illuminate\Auth\AuthenticationException;

class AccountNotSetException extends AuthenticationException
{
    protected $code = 401;
    protected $message = 'The account has not been set.';
}
