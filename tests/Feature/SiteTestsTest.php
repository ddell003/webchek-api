<?php


namespace Tests\Feature;


use App\Models\App;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTestsTest extends TestCase
{
    /**
     * need to make test factory and change to point to tests endpoint
     */
    use RefreshDatabase;
    public function test_must_be_authenticated_to_create_a_test()
    {
        $this->json('GET','/api/apps')->assertStatus(401);

        $this->withoutExceptionHandling();
        $this->authenticate();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);

    }

    public function test_can_create_test()
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

    public function test_can_get_list_of_test()
    {
        $authUser = $this->authenticate();
        App::factory()->count(5)->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);
        self::assertCount(5, $response->json());

    }

    public function test_can_get_test_detail()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('GET', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(200);
        self::assertEquals($app['id'], $response->json('id'));

    }

    public function test_can_update_test_detail()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $app['name'] = "new name";
        $response = $this->json('PUT', '/api/apps/'.$app['id'], $app, $this->headers);
        $response->assertStatus(200);
        self::assertEquals($app['id'], $response->json('id'));
        self::assertEquals($app['name'], $response->json('name'));

    }

    public function test_can_delete_test()
    {
        $authUser = $this->authenticate();
        $app = App::factory()->create(["account_id"=>$authUser->account_id])->toArray();
        $response = $this->json('DELETE', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(204);

        $response = $this->json('GET', '/api/apps/'.$app['id'], [], $this->headers);
        $response->assertStatus(404);
    }
}
