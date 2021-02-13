<?php


namespace Tests\Feature;


use App\Models\App;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AppTest extends TestCase
{
    use RefreshDatabase;
    public function test_must_be_authenticated_to_create_an_app()
    {
        $this->json('GET','/api/apps')->assertStatus(401);

        $this->withoutExceptionHandling();
        $this->authenticate();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);

    }


    public function test_can_create_app()
    {
        $authUser = $this->authenticate();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);
        $body = $response->json();
        self::assertEquals(0, count($body));


        $site = App::factory()->make(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('POST', '/api/apps', $site, $this->headers);
        $response->assertStatus(201);

        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);
        $body = $response->json();
        self::assertEquals(1, count($body));
    }

    public function test_can_get_list_of_app()
    {
        $authUser = $this->authenticate();
        App::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);
        self::assertCount(5, $response->json());

    }

    public function test_can_get_app_detail()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(200);
        self::assertEquals($app['id'], $response->json('id'));

    }

    public function test_can_update_app_detail()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $app['name'] = "new name";
        $response = $this->json('PUT', '/api/apps/'.$app['id'], $app, $this->headers);
        $response->assertStatus(200);
        self::assertEquals($app['id'], $response->json('id'));
        self::assertEquals($app['name'], $response->json('name'));

    }

    public function test_can_delete_app()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('DELETE', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(204);

        $response = $this->json('GET', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(404);
    }
}
