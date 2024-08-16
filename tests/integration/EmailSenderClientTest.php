<?php

namespace SocialNetworksPublisherPHP\Tests;

use PHPUnit\Framework\TestCase;
use EmailSenderClient\EmailSenderClient;

class EmailSenderClientTest extends TestCase
{
    private const TEST_DOMAIN = "http://172.17.0.1:11680/";
    private const EMAIL_CONTENT = "<html><body><h1>This is a test email</h1></body></html>";
    private const EMAIL_SUBJECT = "Integration Test Email Subject";
    private const SENDER_NAME = "Test Sender";
    private const SENDER_EMAIL = "pedrobesseofi@gmail.com";
    private const RECIPIENTS = [
        "Test Recipient, pedro.besse@logipro.com"
    ];
    private const PROVIDER = "Brevo";

    public function testSendEmail(): void
    {
        $client = new EmailSenderClient(domain: self::TEST_DOMAIN);
        $result = $client->sendEmail(
            self::SENDER_NAME,
            self::SENDER_EMAIL,
            self::RECIPIENTS,
            self::EMAIL_SUBJECT,
            self::EMAIL_CONTENT,
            self::PROVIDER
        );
        $this->assertTrue($result);
    }

    public function testFailureSendEmail(): void
    {
        $client = new EmailSenderClient(domain: self::TEST_DOMAIN);
        $result = $client->sendEmail(
            self::SENDER_NAME,
            self::SENDER_EMAIL,
            [], 
            self::EMAIL_SUBJECT,
            "", 
            self::PROVIDER
        );
        $this->assertFalse($result);
    }
}
