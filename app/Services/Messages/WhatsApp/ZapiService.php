<?php

namespace App\Services\Messages\WhatsApp;

use Illuminate\Support\Facades\Http;
use App\Services\Messages\MessageInterface;
use GuzzleHttp\Exception\RequestException;

class ZapiService implements MessageInterface
{
    public function send(string $to, string $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('ZAPI_TOKEN'),
            ])->post(env('ZAPI_ENDPOINT_URL'), [
                'phone' => $to,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return response()->json(['status' => 'success'], $response->status());
            } else {
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
