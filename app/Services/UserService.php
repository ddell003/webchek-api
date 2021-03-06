<?php


namespace App\Services;


use App\Repository\AccountRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function __construct(UserRepository $userRepository, AccountRepository $accountRepository)
    {
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
    }

    public function get()
    {
        return $this->userRepository->get();
    }

    public function getById($id)
    {
        return $this->userRepository->getById($id);
    }

    public function create($data)
    {
        $userData = [
            "account_id"=>Auth::user()->account_id,
            "name"=>$data['name'],
            "email"=>$data['email'],
            "owner"=>Arr::get($data, "owner", 0),
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(80),

        ];

        $user = $this->userRepository->create($userData);
        return $user;
    }

    public function update($id, $data)
    {
        $user = $this->getById($id);
        $userData = [
            "name"=>$data['name'],
            "email"=>$data['email'],
            "owner"=>Arr::get($data, "owner", $user->owner),
        ];
        if($password = Arr::get($data, 'password')){
            $userData['password'] =  Hash::make($data['password']);
        }

        $user = $this->userRepository->update($id, $userData);
        return $user;
    }

    public function createAccount($data)
    {
        $account = $this->accountRepository->create(["name"=>$data['name']]);

        $userData = [
            "account_id"=>$account->id,
            "name"=>$data['fullname'],
            "email"=>$data['email'],
            "owner"=>1,
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(80),

        ];

        $user = $this->userRepository->create($userData);
        return $user;
    }

    public function delete($user_id)
    {
        return $this->userRepository->delete($user_id);
    }


}
