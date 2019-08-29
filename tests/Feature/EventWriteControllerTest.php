<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventWriteControllerTest extends TestCase
{
    public function testSuccessfulCreate()
    {
        $this->withoutExceptionHandling();
        $response = $this->json('POST', '/api/write', [
            'key'   => 'hello',
            'value' => 'world',
            'group' => 'earth',
        ]);
        $response->assertStatus(200)
                 ->assertExactJson(['status' => 'ok']);
    }

    public function testKeyIsRequired()
    {
        $response = $this->json('POST', '/api/write', [
            'value' => '123',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key' => [
                        'The key field is required.',
                    ]
                ]
            ]);
    }

    public function testValueIsRequired()
    {
        $response = $this->json('POST', '/api/write', [
            'key' => 'foo',
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'value' => [
                        'The value field is required.',
                    ]
                ]
            ]);
    }

    public function testErrorIfValuesGreaterThan255()
    {
        $response = $this->json('POST', '/api/write', [
            'key'   => str_pad( 'a', 300, 'a' ),
            'value' => str_pad( 'a', 300, 'a' ),
            'group' => str_pad( 'a', 300, 'a' ),
        ]);
        $response
            ->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'key' => [
                        'The key may not be greater than 255 characters.',
                    ],
                    'value' => [
                        'The value may not be greater than 255 characters.',
                    ],
                    'group' => [
                        'The group may not be greater than 255 characters.',
                    ]
                ]
            ]);
    }
}
