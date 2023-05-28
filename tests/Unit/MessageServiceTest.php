<?php

namespace Tests\Unit;

use Tests\TestCase;

class MessageServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock environment variables
        $this->app->instance('path.config', __DIR__.'/fixtures');
        $this->app->detectEnvironment(function () {
            return 'testing';
        });
    }
}
