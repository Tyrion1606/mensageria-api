<?php

namespace App\Http\Controllers;

// Importando as classes necessárias
use Illuminate\Http\Request;
use Twilio\Rest\Client as TwilioClient;
use GuzzleHttp\Client as GuzzleClient; //necessário para usar o facade?
use Illuminate\Support\Facades\Http;
use SendGrid\Mail\Mail;
use App\Http\Requests\MessageFormRequest;
use App\Services\Messages\SMS\TwilioService;
use App\Services\Messages\WhatsApp\ZapiService;
use App\Services\Messages\Email\SendGridService;


class MessagingController extends Controller
{
    // Função de envio
    public function send(MessageFormRequest $request, $channel)
    {
        //dd('send');
        // Validação da requisição
        $data = $request->validated();

        // Verifica qual canal foi selecionado e chama a função apropriada
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
                // Retorna um erro se o canal for inválido
                return response()->json(['error' => 'Invalid channel'], 400);
        }

        $response = $service->send($data['to'], $data['message']);
        return response()->json(['status' => 'success'], $response->status());
    }
}
