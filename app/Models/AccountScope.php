<?php


namespace App\Models;


use App\Exceptions\AccountNotSetException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AccountScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     * @throws \Exception
     */
    public function apply(Builder $builder, Model $model)
    {
        $found = false;

        if($user = Auth::user()){
            if($account_id = $user->account_id){
                $found = true;
                $builder->where($model->getTable() . '.account_id', $account_id);
            }
        }

        if (!$found) {
            //throw new AccountNotSetException();
        }
    }
}
