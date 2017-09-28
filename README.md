# Nette Foundation for Emails

[ZURB Foundation for Emails](https://github.com/zurb/foundation-emails) integration into
[Nette framework](https://github.com/nette/nette) consisting of a mail factory to create email messages
with [nette/latte](https://github.com/nette/latte) templates using [Inky](https://github.com/zurb/inky) markup
and an optional simple mail service to send the messages.

-----

[![Build Status](https://travis-ci.org/teekey99/nette-foundation-emails.svg?branch=master)](https://travis-ci.org/teekey99/nette-foundation-emails)
[![Coverage Status](https://coveralls.io/repos/github/teekey99/nette-foundation-emails/badge.svg)](https://coveralls.io/github/teekey99/nette-foundation-emails)
[![Licence](https://img.shields.io/packagist/l/vitrozsival/nette-foundation-emails.svg?style=flat-square)](https://packagist.org/packages/vitrozsival/nette-foundation-emails)

## Requirements

- PHP 7.0+
- Nette 2.4+

## Installation

```bash
composer require vitrozsival/nette-foundation-emails
```

## Configuration

Register `mailFactory` extension in your config:

```neon
extensions:
	mailFactory: NetteFoundationEmails\DI\MailFactoryExtension
```

Then you can configure the extension with following parameters:

```neon
mailFactory:
	email: 'your@email.com' # default email to be set for messages as 'sent from'
	name: 'Your Name' # default name to be set for messages as 'sent from'
	cssFilename: 'emails.css' # default stylesheet filename located in resources directory
	resourcesDir: ./resources # default resources directory path (stylesheets, images, etc.)
	templatesDir: ./templates # default message templates directory path
```

## Usage

The `MailFactory` has only one method called `create` to be used with your email messages. It accepts two arguments:

#### `messageTemplate`

- an absolute path to your own message template
- or a path relative to the configured templates directory path (if using a relative path, you can omit `.latte`
extension as it will be added if needed)
- if no template is found, the method throws `MailFactoryException`

#### `cssFile` (optional)

- an absolute path to your own build of Foundation for Emails stylesheet
- or a path relative to the configured resources directory path
- if none provided, the factory will look for the configured `cssFilename` inside the configured `resourcesDir`
- if no stylesheet is found at all, default `resources/foundation-emails.min.css` will be used

The mail factory will use any valid Nette `ITranslator` from your DI container to translate your messages.

## Mail service

You can also use the simple `MailService` that ships with this package to send messages created
with the `MailFactory`. The service gets registered with the `MailFactoryExtension` therefor it is present in your DI
container.

The service has three methods - to `createMessage` through the `MailFactory` (this method accepts same arguments
as `create` method of `MailFactory` and only returns the resulting message), to create a `link` to your website
using Nette `LinkGenerator` and to send the created `Message`. The `sendMessage` method throws `MailServiceException`
if the message could not be sent through Nette `IMailer`.

Anyway, feel free to implement the `MailFactory` into your own mail service as you need.

## License

[MIT License](LICENSE)