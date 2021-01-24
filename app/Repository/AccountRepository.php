<?php


namespace App\Repository;


use App\Models\Account;

class AccountRepository extends EloquentRepository
{
    protected function getModel()
    {
        return new Account();
    }
}
