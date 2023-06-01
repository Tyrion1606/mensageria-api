<?php

namespace App\Services\Messages\WhatsApp;

use Illuminate\Support\Facades\Http;
use App\Services\Messages\MessageInterface;
use GuzzleHttp\Exception\RequestException;

class ZapiService implements MessageInterface
{
    // Implementação do método send da interface MessageInterface
    public function send(string $to, string $message)
    {
        try {
            // Faz a chamada HTTP POST para a API do Z-API usando o Facade HTTP
            // Adiciona o token de autorização no cabeçalho
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('ZAPI_TOKEN'),
            ])->post(env('ZAPI_ENDPOINT_URL'), [
                'phone' => $to,
                'message' => $message,
            ]);

            // Verifica se a resposta é bem sucedida
            if ($response->successful()) {
                // Retorna a resposta do Z-API
                return response()->json(['status' => 'success'], $response->status());
            } else {
                // Retorna um erro se a resposta for mal sucedida
                // dd('deu ruim', $response->json(),$response->status());
                return response()->json([
                    'status' => 'success',
                    'message' => $response->json()['error']
                ], $response->status());
            }
        } catch (RequestException $e) {
            // Retorna um erro se uma exceção for lançada durante a chamada HTTP
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
