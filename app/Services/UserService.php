<?php


namespace App\Services;


use App\Repository\UserRepository;

class UserService
{
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function get()
    {
        return $this->userRepository->get();
    }
}
