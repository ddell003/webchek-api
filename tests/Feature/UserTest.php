<?php


namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
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

    public function test_can_retrieve_user()
    {
        $this->json('GET','/api/users')->assertStatus(401);

        $this->withoutExceptionHandling();
        $user = $this->authenticate();
        $response = $this->json('GET', '/api/users/'.$user->id, [], $this->headers);
        $response->assertStatus(200);
        self::assertEquals($user->id, $response->json("id"));
    }



    public function test_can_create_users()
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

    public function test_users_require_unique_email()
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

    public function test_can_get_list_of_users()
    {
        $authUser = $this->authenticate();
        User::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);
        self::assertCount(6, $response->json());

    }

    public function test_can_delete_users()
    {
        $authUser = $this->authenticate();
        User::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);
        $users = $response->json();

        $response = $this->json('DELETE', '/api/users/'.$users[3]['id'], [], $this->headers);
        $response->assertStatus(204);
    }

    public function test_can_update_users()
    {
        $authUser = $this->authenticate();
        User::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/users', [], $this->headers);
        $response->assertStatus(200);
        $users = $response->json();
        $updatedUser = $users[3];
        $updatedUser['name'] = "newName";

        $response = $this->json('PUT', '/api/users/'.$updatedUser['id'],$updatedUser, $this->headers);
        $response->assertStatus(200);

        self::assertEquals($updatedUser['name'], $response->json("name"));
    }
}
