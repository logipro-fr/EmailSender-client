<?php

namespace EmailSenderClient\Tests;

use EmailSenderClient\EmailSenderClient;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function Safe\file_get_contents;

class EmailSenderClientTest extends TestCase
{
    public function testGetDomain(): void
    {
        $client = new EmailSenderClient();
        $this->assertEquals(EmailSenderClient::DEFAULT_DOMAIN, $client->getDomain());
    }

    public function testSendEmail(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $responseMock->method('getStatusCode')
            ->willReturn(201);

        $httpClientMock->method('request')
            ->willReturnCallback(function ($method, $url, $options) use ($responseMock) {
                $this->assertEquals('https://emailsender.logipro.fr/api/v1/email/send', $url);

                $data = json_encode([
                    'sender' => 'Pedro, pedro@gmail.com',
                    'recipient' => ['Pedro, pedro@gmail.com'],
                    'subject' => 'Email test',
                    'content' => '<html><body><h1>This is a test email</h1></body></html>',
                    'provider' => 'Brevo',
                ]);

                $expectedOptions = [
                    'headers' => [
                        'Content-Type' => 'application/json',
                    ],
                    'body' => $data,
                ];

                $this->assertEquals($expectedOptions, $options);

                return $responseMock;
            });

        $client = new EmailSenderClient($httpClientMock);

        $result = $client->sendEmail(
            'Pedro',
            'pedro@gmail.com',
            ['Pedro, pedro@gmail.com'],
            'Email test',
            '<html><body><h1>This is a test email</h1></body></html>',
            'Brevo'
        );

        $this->assertTrue($result);
    }

    public function testHttpClientProvided(): void
    {
        /** @var HttpClientInterface */
        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $client = new EmailSenderClient($httpClientMock);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('http');
        $property->setAccessible(true);
        $http = $property->getValue($client);

        $this->assertSame($httpClientMock, $http);
    }

    public function testHttpClientDefault(): void
    {
        $client = new EmailSenderClient();

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('http');
        $property->setAccessible(true);
        $http = $property->getValue($client);

        $this->assertInstanceOf(HttpClientInterface::class, $http);
        $this->assertNotSame(HttpClient::create(), $http);
    }

    public function testFailureSendEmail(): void
    {
        $errorResponse = new MockResponse(
            file_get_contents(getcwd() . "/tests/ressources/errorResponse.json"),
            ['http_code' => 400]
        );
        $httpClient = new MockHttpClient($errorResponse, 'https://test.com/api/v1/email/send');
        $client = new EmailSenderClient($httpClient, "https://test.com/");
        $result = $client->sendEmail(
            'Pedro',
            'pedro@gmail.com',
            ['Pedro, pedro@gmail.com'],
            'Email test',
            '<html><body><h1>This is a test email</h1></body></html>',
            'Brevo'
        );
        $this->assertFalse($result);
    }
}
