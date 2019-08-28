<?php

namespace Tests\Feature;

use App\Event;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

    public function testKeyValueAreRequired()
    {
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json('GET', '/api/read', [], [
            'HTTP_Authorization' => 'Bearer 123abc'
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key'   => [
                        'The key field is required.',
                    ],
                    'value' => [
                        'The value field is required.',
                    ]
                ]
            ]);
    }

    public function testGetWithoutGroup()
    {
        putenv('SET_ACCESS_TOKEN=123abc');
        $response = $this->json(
            'GET',
            '/api/read',
            [
                'key'   => 'foo',
                'value' => 'bar',
            ],
            [
                'HTTP_Authorization' => 'Bearer 123abc'
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
                'HTTP_Authorization' => 'Bearer 123abc'
            ]
        );
        $response->assertStatus(200)
                  ->assertSee(1);
    }
}
