<?php

namespace Tests\Unit;

use App\Services\Messages\WhatsApp\ZapiService;
use Illuminate\Support\Facades\Http;

class ZapiServiceTest extends MessageServiceTest
{
    public function testSendMethod()
    {
        // Mock Http facade
        Http::fake();

        // Define expected data
        $to = '1234567890';
        $message = 'Test message';

        // Instantiate service
        $service = new ZapiService();

        // Perform the test
        $response = $service->send($to, $message);

        // Assert a POST request was sent to the expected endpoint with the correct data
        Http::assertSent(function ($request) use ($to, $message) {
            return $request->url() == env('ZAPI_ENDPOINT_URL') &&
                   $request['phone'] == $to &&
                   $request['message'] == $message &&
                   $request->header('Authorization') == 'Bearer ' . env('ZAPI_TOKEN');
        });

        // Assert response was returned
        $this->assertNotNull($response);
    }
}
