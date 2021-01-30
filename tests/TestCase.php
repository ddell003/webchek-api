<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Auth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public $account_id;
    public $headers = [];

    public function authenticate($overrides = [])
    {
        $user = User::factory()->create($overrides);

        //Auth::setUser($user);
        $this->headers = [
            'Authorization' => 'Bearer '.$user->api_token,
        ];
        $this->account_id = $user->account_id;
        return $user;
    }
}
