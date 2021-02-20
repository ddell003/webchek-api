<?php


namespace App\Http\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\LogInRequest;
use App\Models\User;
use App\Services\helpers\RunTest;
use App\Services\TestService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class LoginController extends Controller
{
    private $userService;
    private $testService;

    public function __construct(UserService $userService, TestService $testService)
    {
         $this->userService = $userService;
         $this->testService = $testService;
    }

    public function login(LogInRequest $request){
        $data = $request->all();
        $credentials = ["email"=>$data["email"], "password"=>$data["password"]];
        if(!Auth::once($credentials)) {
            // Invalid user credentials; throw Exception.
            return Response::json(["Incorrect Credentials"], 403);
        }

        $user = Auth::getUser();
        $formattedUser = [
            "id"=>$user->id,
            "name"=>$user->name,
            "created_at"=>$user->created_at,
            "updated_at"=>$user->updated_at,
            "owner"=>$user->owner,
            "api_token"=>$user->api_token,
        ];
        //lets run the account tests each time a user logs in
        $this->testService->runAccountTests();
        return $formattedUser;

    }
}
