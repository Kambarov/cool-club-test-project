<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendMessage()
    {
        #Run php artisan optimize:clear every time when you try to test
        Artisan::call('optimize:clear');

        #Test for success validation
        $content = 'test';
        $response = $this->postJson('/api/send-message', ['content' => $content]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'content' => $content
                ],
                'message' => 'Your mail was successfully sent'
            ], $strict = false);

        #Test for validation fail
        $response = $this->postJson('/api/send-message', ['content' => '']);

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'content' => ['The content field is required.']
                ]
            ], $strict = true);

        #Test for throttle fail
        $response = $this->postJson('/api/send-message', ['content' => '']);

        $response
            ->assertStatus(429)
            ->assertJson([
                'message' => 'Too Many Attempts.'
            ], $strict = false);
    }
}
