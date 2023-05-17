<?php

namespace App\Http\Controllers;

// Importando as classes necessárias
use Illuminate\Http\Request;
use Twilio\Rest\Client as TwilioClient;
use GuzzleHttp\Client as GuzzleClient; //necessário para usar o facade?
use Illuminate\Support\Facades\Http;
use SendGrid\Mail\Mail;

class MessagingController extends Controller
{
    // Função de envio
    public function send(Request $request, $channel)
    {
        // Validação dos campos obrigatórios da requisição
        $data = $request->validate([
            'to' => 'required',
            'message' => 'required',
        ]);

        // Verifica qual canal foi selecionado e chama a função apropriada
        switch ($channel) {
            case 'sms':
                return $this->sendSMS($data);
            case 'whatsapp':
                return $this->sendWhatsApp($data);
            case 'email':
                return $this->sendEmail($data);
            default:
                // Retorna um erro se o canal for inválido
                return response()->json(['error' => 'Invalid channel'], 400);
        }
    }

    // Função para enviar SMS usando Twilio
    private function sendSMS($data)
    {
        // Inicializa o cliente Twilio
        $twilio = new TwilioClient(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));

        // Envia a mensagem
        $twilio->messages->create($data['to'], [
            'from' => env('TWILIO_PHONE'),
            'body' => $data['message'],
        ]);

        // Retorna uma resposta de sucesso
        return response()->json(['status' => 'success'], 200);
    }

    // Função para enviar mensagem no WhatsApp usando Z-API
    private function sendWhatsApp($data)
    {
        // Faz a chamada HTTP POST para a API do Z-API usando o Facade HTTP
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('ZAPI_TOKEN'),
        ])->withOptions([
            'verify' => false, // Desativa a verificação SSL     PERIGO!!!! perguntar para o Douglas
        ])->post(env('ZAPI_ENDPOINT_URL'), [
            'phone' => $data['to'],
            'message' => $data['message'],
        ]);

        // Retorna a resposta do Z-API
        return response()->json($response->json(), $response->status());
    }

    // Função para enviar e-mails usando SendGrid
    private function sendEmail($data)
    {
        // Cria um novo objeto de e-mail
        $email = new Mail();
        $email->setFrom('noreply@example.com', 'Your Company');
        $email->addTo($data['to']);
        $email->setSubject('Welcome to Ubuntu');
        $email->addContent('text/html', $data['message']);

        // Inicializa o cliente SendGrid
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        // Envia o e-mail
        $response = $sendgrid->send($email);

        // Retorna uma resposta de sucesso
        return response()->json(['status' => 'success'], $response->statusCode());
    }
}
