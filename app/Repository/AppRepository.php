<?php


namespace App\Repository;


use App\Models\App;

class AppRepository extends EloquentRepository
{
    protected function getModel()
    {
        return new App();
    }
}

