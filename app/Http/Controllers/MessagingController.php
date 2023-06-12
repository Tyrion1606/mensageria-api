<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageFormRequest;
use App\Services\Messages\SMS\TwilioService;
use App\Services\Messages\WhatsApp\ZapiService;
use App\Services\Messages\Email\SendGridService;


class MessagingController extends Controller
{
    public function send(MessageFormRequest $request, $channel)
    {
        $data = $request->validated();

        switch ($channel) {
            case 'sms':
                $service = new TwilioService();
                break;
            case 'whatsapp':
                $service = new ZapiService();
                break;
            case 'email':
                $service = new SendGridService();
                break;
            default:
                return response()->json(['error' => 'Invalid channel'], 400);
        }

        $response = $service->send($data['to'], $data['message']);

        return $response;
    }
}
