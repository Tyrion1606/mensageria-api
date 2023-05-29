<?php

namespace Tests\Unit;

use Tests\TestCase;
use Http\Controllers\AuthController;

class LoginTest extends TestCase
{
    public function test_login()
    {
        $response = $this->postJson('/api/auth/login',[
            'email' => 'admin@ubuntueducacao.com.br',
            'password' =>env('ADMIN_PASSWORD')
        ]);

        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'token'
            ]);
    }

    public function test_wrong_password()
    {
        $response = $this->postJson('/api/auth/login',[
            'email' => 'admin@ubuntueducacao.com.br',
            'password' =>'wrong_password'
        ]);

        $response
            ->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid email or password.'
            ]);
    }

    public function test_logout()
    {
        $loginResponse = $this->postJson('/api/auth/login',[
            'email' => 'admin@ubuntueducacao.com.br',
            'password' =>env('ADMIN_PASSWORD')
        ]);

        $data = $loginResponse->json();
        $token = $data['token'];

        $response = $this->postJson('/api/auth/logout',[],['Authorization' => 'Bearer ' . $token]);

        $response
            ->assertStatus(204);
    }


    public function test_full_logout()
    {
        $loginResponse = $this->postJson('/api/auth/login',[
            'email' => 'admin@ubuntueducacao.com.br',
            'password' =>env('ADMIN_PASSWORD')
        ]);

        $data = $loginResponse->json();
        $token = $data['token'];

        $response = $this->postJson('/api/auth/logout/all',[],['Authorization' => 'Bearer ' . $token]);

        $response
            ->assertStatus(204);
    }

}
