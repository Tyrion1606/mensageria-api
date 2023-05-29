<?php

namespace Tests\Unit;

use Tests\TestCase;
use Http\Controllers\AuthController;

class SendMessageTest extends TestCase
{
    public function test_send()
    {
        //LOGIN TO TAKE A TOKEN
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'admin@ubuntueducacao.com.br',
            'password' => env('ADMIN_PASSWORD')
        ]);

        $data = $loginResponse->json();
        $token = $data['token'];



        //whatsapp
        $response = $this->postJson(
            '/api/whatsapp',
            [
                'to' => '000000000',
                'message' => 'test message'
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );
        $response
            ->assertJson([
                'status' => 'success'
            ]);

        //email
        $response = $this->postJson(
            '/api/email',
            [
                'to' => 'test@ubuntueducacao.com.br',
                'message' => 'API test message'
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );
        $response
            ->assertJson([
                'status' => 'success'
            ]);

        //sms
        $response = $this->postJson(
            '/api/sms',
            [
                'to' => env('TWILIO_PHONE'),
                'message' => 'API test message'
            ],
            [
                'Authorization' => 'Bearer ' . $token
            ]
        );
        $response
            ->assertJson([
                'status' => 'success'
            ]);
    }
}
