<?php

namespace Tests\Services\Messages\Email;

use App\Services\Messages\Email\SendGridService;
use PHPUnit\Framework\TestCase;
use SendGrid\Mail\Mail;
use SendGrid\Response;

class SendGridServiceTest extends TestCase
{
    public function testSend()
    {
        $to = 'test@example.com';
        $message = 'This is a test message';
        $subject = 'Test Subject';

        $mockMail = $this->createMock(Mail::class);
        $mockMail->expects($this->once())->method('setFrom')->willReturn($mockMail);
        $mockMail->expects($this->once())->method('addTo')->willReturn($mockMail);
        $mockMail->expects($this->once())->method('setSubject')->willReturn($mockMail);
        $mockMail->expects($this->once())->method('addContent')->willReturn($mockMail);

        $mockResponse = $this->createMock(Response::class);
        $mockResponse->method('statusCode')->willReturn(202);

        $mockSendGrid = $this->getMockBuilder(\SendGrid::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $mockSendGrid->expects($this->once())
                     ->method('send')
                     ->willReturn($mockResponse);

        $sendGridService = new SendGridService($mockMail, $mockSendGrid);

        $result = $sendGridService->send($to, $message, $subject);
        $this->assertEquals(202, $result->getStatusCode());
    }
}
