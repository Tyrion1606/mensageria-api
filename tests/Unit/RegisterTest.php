<?php

namespace Tests\Unit;

use Tests\TestCase;
use Http\Controllers\AuthController;

class RegisterTest extends TestCase
{
    public function test_already_registered()
    {
        $response = $this->postJson('/api/auth/register',[
            'name' => 'test',
            'email' => 'test@test',
            'password' => 'senha'
        ]);
        $response = $this->postJson('/api/auth/register',[
            'name' => 'test',
            'email' => 'test@test',
            'password' => 'senha'
            ]);
        $response->assertStatus(422);
    }

    public function test_empty_register()
    {
        $response = $this->postJson('/api/auth/register');
        $response->assertStatus(422);
    }

    public function test_untokenized_register()
    {

        $response = $this->postJson('/api/auth/register');
        $response->assertStatus(422);
    }
}
