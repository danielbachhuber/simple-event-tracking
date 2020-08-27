<?php

namespace Tests\Feature;

use App\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EventReadControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        (new Event([
            'key'   => 'foo',
            'value' => 'bar',
        ]))->save();
        (new Event([
            'key'   => 'foo',
            'value' => 'bar',
        ]))->save();
        (new Event([
            'key'   => 'foo',
            'value' => 'bar',
            'group' => 'baz',
        ]))->save();
        (new Event([
            'key'   => 'foo',
            'value' => 'apple',
        ]))->save();
        putenv('SET_ACCESS_TOKEN=');
    }

    public function testRequiresAuthentication()
    {
        $response = $this->json('GET', '/api/read', [
            'key'   => 'foo',
            'value' => 'bar',
        ]);
        $response->assertStatus(401);
    }

    public function testKeyIsRequired()
    {
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json('GET', '/api/read', [], [
            'HTTP_Authorization' => 'Bearer 123abc',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key'   => [
                        'The key field is required.',
                    ],
                ],
            ]);
    }

    public function testGetOnlyKey()
    {
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json(
            'GET',
            '/api/read',
            [
                'key'   => 'foo',
            ],
            [
                'HTTP_Authorization' => 'Bearer 123abc',
            ]
        );
        $response->assertStatus(200)
                  ->assertExactJson([
                    'bar'   => 3,
                    'apple' => 1,
                  ]);
    }

    public function testGetWithoutGroup()
    {
        $this->withoutExceptionHandling();
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json(
            'GET',
            '/api/read',
            [
                'key'   => 'foo',
                'value' => 'bar',
            ],
            [
                'HTTP_Authorization' => 'Bearer 123abc',
            ]
        );
        $response->assertStatus(200)
                  ->assertSee(3);
    }

    public function testGetWithGroup()
    {
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json(
            'GET',
            '/api/read',
            [
                'key'   => 'foo',
                'value' => 'bar',
                'group' => 'baz',
            ],
            [
                'HTTP_Authorization' => 'Bearer 123abc',
            ]
        );
        $response->assertStatus(200)
                  ->assertSee(1);
    }
}
