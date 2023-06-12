<?php

namespace App\Services\Messages\SMS;

use Twilio\Rest\Client as TwilioClient;
use App\Services\Messages\MessageInterface;
use Twilio\Exceptions\RestException;

class TwilioService implements MessageInterface
{
    public function send(string $to, string $message)
    {
        $twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        try {
            $twilio->messages->create($to, [
                'from' => env('TWILIO_PHONE'),
                'body' => $message,
            ]);
            return response()->json(['status' => 'success'], 200);
        } catch (RestException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
