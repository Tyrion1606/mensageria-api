<?php

namespace App\Services\Messages\SMS;

use Twilio\Rest\Client as TwilioClient;
use App\Services\Messages\MessageInterface;
use Twilio\Exceptions\RestException;

class TwilioService implements MessageInterface
{
    // Implementação do método send da interface MessageInterface
    public function send(string $to, string $message)
    {
        // Inicializa o cliente Twilio com SID e Token de autenticação
        $twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        try {
            // Envia a mensagem
            $twilio->messages->create($to, [
                'from' => env('TWILIO_PHONE'),
                'body' => $message,
            ]);

            // dump('bom');
            // Retorna uma resposta de sucesso
            return response()->json(['status' => 'success'], 200);
        } catch (RestException $e) {
            // dump('ruim');
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }
}
