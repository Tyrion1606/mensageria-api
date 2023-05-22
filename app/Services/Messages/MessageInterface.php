<?php

namespace App\Services\Messages;

interface MessageInterface
{
    public function send(string $to, string $message);
}
