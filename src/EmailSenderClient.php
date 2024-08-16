<?php

namespace EmailSenderClient;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EmailSenderClient
{
    public const DEFAULT_DOMAIN = "https://emailsender.logipro.fr/";
    private const PUBLISH_ENDPOINT = "api/v1/email/send";
    private HttpClientInterface $http;

    public function __construct(
        HttpClientInterface $http = null,
        private string $domain = self::DEFAULT_DOMAIN,
    ) {
        $this->http = $http ?? HttpClient::create();
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function sendEmail(
        string $senderName,
        string $senderEmail,
        array $recipients, 
        string $subject,
        string $content,
        string $provider = "Brevo"
    ): bool {
        $response = $this->http->request(
            "POST",
            $this->domain . self::PUBLISH_ENDPOINT,
            $this->constructSendEmailRequest($senderName, $senderEmail, $recipients, $subject, $content, $provider)
        );

        if ($response->getStatusCode() === 201) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array<string, array<string>|string>
     */
    private function constructSendEmailRequest(
        string $senderName,
        string $senderEmail,
        array $recipients,
        string $subject,
        string $content,
        string $provider
    ): array {
        $data = json_encode([
            'sender' => "$senderName, $senderEmail",
            'recipient' => $recipients,
            'subject' => $subject,
            'content' => $content,
            'provider' => $provider,
        ]);

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => $data,
        ];

        return $options;
    }
}
