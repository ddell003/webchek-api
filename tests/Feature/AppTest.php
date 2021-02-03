<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AppTest extends TestCase
{
    use RefreshDatabase;
    public function test_must_be_authenticated_to_create_an_app()
    {
        $this->json('GET','/api/users')->assertStatus(401);

        $this->withoutExceptionHandling();
        $this->authenticate();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);

    }


    public function test_can_create_app()
    {
        $authUser = $this->authenticate();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);
        $body = $response->json();
        self::assertEquals(1, count($body));


        $user2 = User::factory()->make(["account_id"=>$authUser->account_id])->toArray();
        $user2['emails'] = "user2@gmail.com";
        $user2['password'] = "password";
        $user2['name'] = "user 2";
        $response = $this->json('POST', '/api/users', $user2, $this->headers);
        $response->assertStatus(201);
    }

    public function test_app_require_name()
    {
        $authUser = $this->authenticate();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);

        $user2 = User::factory()->make(["account_id"=>$authUser->account_id])->toArray();
        $usedUser =  $authUser->toArray();
        $user2['emails'] =$usedUser['emails'];
        $user2['password'] = "password";
        $user2['name'] = "user 2";

        $response2 = $this->json('POST', '/api/users', $user2, $this->headers);
        $response2->assertStatus(422);
    }

    public function test_can_get_list_of_app()
    {
        $authUser = $this->authenticate();
        User::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);
        self::assertCount(6, $response->json());

    }

    public function test_can_delete_app()
    {

    }
}
