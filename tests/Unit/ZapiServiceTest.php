<?php

namespace Tests\Unit;

use App\Services\Messages\WhatsApp\ZapiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZapiServiceTest extends TestCase
{
    public function testSendMethod()
    {
        // Mock Http facade
        Http::fake();

        // LOGIN TO TAKE A TOKEN
        $loginResponse = $this->postJson('/api/auth/login',[
            'email' => 'admin@ubuntueducacao.com.br',
            'password' =>env('ADMIN_PASSWORD')
        ]);
        $data = $loginResponse->json();
        $token = $data['token'];

        // send message
        $to = '1234567890';
        $message = 'Test message';
        $service = new ZapiService();
        $response = $service->send($to, $message);


        // // Assert a POST request was sent to the expected endpoint with the correct data
        // Http::assertSent(function ($request) use ($to, $message) {
        //     return $request->url() == env('ZAPI_ENDPOINT_URL') &&
        //            $request['phone'] == $to &&
        //            $request['message'] == $message &&
        //            $request->header('Authorization') == 'Bearer ' . env('ZAPI_TOKEN');
        // });

        // Assert response was returned
        $this->assertNotNull($response);
    }
}
