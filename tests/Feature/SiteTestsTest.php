<?php


namespace Tests\Feature;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiteTestsTest extends TestCase
{
    use RefreshDatabase;
    public function test_must_be_authenticated_to_create_a_test()
    {
        $this->json('GET','/api/apps')->assertStatus(401);

        $this->withoutExceptionHandling();
        $this->authenticate();
        $response = $this->json('GET', '/api/apps', [], $this->headers);
        $response->assertStatus(200);

    }
}
