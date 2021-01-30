<?php


namespace App\Http\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Response;

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

    public function show(User $user)
    {
        return $this->userService->getById($user->id);
    }

    public function store(CreateUserRequest $request)
    {
        return $this->userService->create($request->all());
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->userService->update($user->id, $request->all());
    }

    public function destroy(User $user)
    {
        $this->userService->delete($user->id);
        return Response::json([], 204);
    }
}
