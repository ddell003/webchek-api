<?php


namespace App\Repository;


use App\Models\Test;

class TestRepository extends EloquentRepository
{
    protected function getModel()
    {
        return new Test();
    }
}

