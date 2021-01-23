<?php


namespace App\Repository;


use App\Models\User;

class UserRepository extends EloquentRepository
{
    protected function getModel()
    {
        return new User();
    }
}
