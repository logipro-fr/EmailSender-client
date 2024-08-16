# Email Sender Client

A PHP component to use the EmailSender API within your PHP project.

**EmailSender** allows you to send email messages using the API.

## Features

### Available Methods

**EmailSender client** provides one public method:

* `sendEmail(string $senderName, string $senderEmail, array $recipients, string $subject, string $content, string $provider = "Brevo")`: Sends an email with the specified `senderName`, `senderEmail`, `recipients`, `subject`, and `content` using the specified email `provider`. Returns a boolean indicating success.

## Installation

```shell
composer require logipro/EmailSender-client
```

## To contribute to Email Sender Client 

### Requirements:
* Docker
* Git
* A bash shell

### Unit tests
Run unit tests with:

```shell
bin/phpunit
```

### Integration tests
Run integration tests with:

```shell
bin/phpunit-integration
```
**integration tests can only be run if you have a running [EmailSender](https://github.com/logipro-fr/emailsender.git) instance**

### Quality
#### Some indicators:
* PHP CodeSniffer (PSR12)
* PHPStan level 9
* Test coverage = 100%
* Mutation Score Indicator (MSI) = 100%


#### Quick check with:
```shell
./codecheck
```


#### Check coverage with:
```shell
bin/phpunit --coverage-html var
```
Then, view the coverage report in your browser at 'var/index.html'.


#### Check infection with:
```shell
bin/infection
```
Then, view the infection report in your browser at 'var/infection.html'.