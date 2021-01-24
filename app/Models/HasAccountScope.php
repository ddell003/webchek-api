<?php

namespace App\Models;

trait HasAccountScope
{
    protected static function bootHasAccountScope()
    {
        static::addGlobalScope(new AccountScope());
        static::observe(new  AccountTenantObserver());
    }
}
