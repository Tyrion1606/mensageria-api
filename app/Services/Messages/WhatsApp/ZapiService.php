<?php

namespace App\Services\Messages\WhatsApp;

use Illuminate\Support\Facades\Http;
use App\Services\Messages\MessageInterface;

class ZapiService implements MessageInterface
{
    // Implementação do método send da interface MessageInterface
    public function send(string $to, string $message)
    {
        // Faz a chamada HTTP POST para a API do Z-API usando o Facade HTTP
        // Adiciona o token de autorização no cabeçalho
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('ZAPI_TOKEN'),
        ])->post(env('ZAPI_ENDPOINT_URL'), [
            'phone' => $to,
            'message' => $message,
        ]);

        // Retorna a resposta do Z-API
        return $response;
    }
}
