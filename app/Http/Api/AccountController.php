<?php


namespace App\Http\Api;


use App\Http\Requests\AccountRequest;
use App\Http\Requests\MealRequest;
use App\Http\Resources\DefaultResource;
use App\Services\UserService;

class AccountController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        return $this->userService = $userService;
    }

    public function store(AccountRequest $request)
    {
        return $this->userService->createAccount($request->all());
    }
}
