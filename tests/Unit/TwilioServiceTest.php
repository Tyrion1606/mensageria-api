<?php

namespace Tests\Unit;

use App\Services\Messages\SMS\TwilioService;
use Twilio\Rest\Client as TwilioClient;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;

class TwilioServiceTest extends TestCase
{
    public function testSendMethod()
    {
        // Mock Twilio dependencies
        $twilio = $this->createMock(TwilioClient::class);

        $twilio->messages = $this->getMockBuilder(stdClass::class)
                                 ->addMethods(['create'])
                                 ->getMock();

        $twilio->messages->expects($this->once())
                         ->method('create')
                         ->willReturn(true);

        // Instantiate service with mocked dependencies
        $service = new TwilioService($twilio);

        // Perform the test
        $result = $service->send('1234567890', 'Test message');

        // Assert a json response was returned with status success
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals('success', $result->getData()->status);
    }
}
