<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountTenantObserver
{
    public function saving(Model $model)
    {
        if(Auth::user()){
            $model->account_id = Auth::user()->account_id;
        }

    }
}
