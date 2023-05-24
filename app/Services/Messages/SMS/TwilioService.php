<?php

namespace App\Services\Messages\SMS;

use Twilio\Rest\Client as TwilioClient;
use App\Services\Messages\MessageInterface;

class TwilioService implements MessageInterface
{
    // Implementação do método send da interface MessageInterface
    public function send(string $to, string $message)
    {
        // Inicializa o cliente Twilio com SID e Token de autenticação
        $twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // Envia a mensagem
        $twilio->messages->create($to, [
            'from' => env('TWILIO_PHONE'),
            'body' => $message,
        ]);

        // Retorna uma resposta de sucesso
        return response()->json(['status' => 'success'], 200);
    }
}
