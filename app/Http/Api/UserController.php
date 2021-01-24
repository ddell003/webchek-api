<?php


namespace App\Http\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        return $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->get();
    }

    public function store(CreateUserRequest $request)
    {
        return $this->userService->create($request->all());
    }
}
