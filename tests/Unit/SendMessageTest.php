<?php

namespace Tests\Unit;

use Tests\TestCase;
use Http\Controllers\AuthController;

class SendMessageTest extends TestCase
{
    public function test_send()
    {
        $token = $this->getAuthToken(); // TAKE A TOKEN

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

    public function test_auth_fail()
    {
        $response = $this->postJson(
            '/api/whatsapp',
            [
                'to' => '000000000',
                'message' => 'test message'
            ],
            [
                'Authorization' => 'Bearer invalid_token'
            ]
        );

        $response
            ->assertStatus(401);
    }

    public function test_invalid_phone_number()
    {
        $response = $this->postJson(
            '/api/sms',
            [
                'to' => 'invalid_number',
                'message' => 'test message'
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ]
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => '[HTTP 400] Unable to create record: The \'To\' number  is not a valid phone number.'
            ]);
    }

    public function test_invalid_email_address()
    {
        $response = $this->postJson(
            '/api/email',
            [
                'to' => 'invalid_email',
                'message' => 'test message'
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ]
        );

        $response
        ->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'message' => '"$emailAddress" must be a valid email address. Got: invalid_email'
            ]);
    }



    public function test_zapi_nosubscription()
    {
        $response = $this->postJson(
            '/api/whatsapp',
            [
                'to' => '000000000',
                'message' => 'test message'
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ]
        );

        $response
            ->assertStatus(400)
            ->assertJson([
                'status' => 'success',
                'message' => 'To continue sending a message, you must subscribe to this instance again'
            ]);
    }

    public function test_empty_message()
    {
        $response = $this->postJson(
            '/api/sms',
            [
                'to' => env('TWILIO_PHONE'),
                'message' => ''
            ],
            [
                'Authorization' => 'Bearer ' . $this->getAuthToken()
            ]
        );

        dump($response->json(), $response->status());

        $response
            ->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors'
            ]);
    }

    private function getAuthToken() //LOGIN TO TAKE A TOKEN
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'admin@ubuntueducacao.com.br',
            'password' => env('ADMIN_PASSWORD')
        ]);

        $data = $loginResponse->json();
        return $data['token'];
    }
}
