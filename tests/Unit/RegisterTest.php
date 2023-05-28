<?php

namespace Tests\Unit;

use Tests\TestCase;

class LocationStateTest extends TestCase
{
    public function test_empty_register()
    {
        $response = $this->postJson('/api/auth/register');
        $response->assertStatus(422);
        $response->assertJsonCount(3);
    }
}
