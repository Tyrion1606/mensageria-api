<?php

namespace App\Services\Messages\Email;

use SendGrid\Mail\Mail;
use App\Services\Messages\MessageInterface;

class SendGridService implements MessageInterface
{
    // Implementação do método send da interface MessageInterface
    public function send(string $to, string $message, string $subject='Ubuntu Gentech')
    {
        // Cria um novo objeto de e-mail
        $email = new Mail();
        $email->setFrom(env('SENDGRID_FROM'), env('SENDGRID_FROM_NAME'));
        $email->addTo($to);
        $email->setSubject($subject);
        $email->addContent('text/html', $message);

        // Inicializa o cliente SendGrid com a chave da API
        $sendgrid = new \SendGrid(env('SENDGRID_API_KEY'));

        // Envia o e-mail
        $response = $sendgrid->send($email);

        // Retorna uma resposta de sucesso
        return response()->json(['status' => 'success'], $response->statusCode());
    }
}
