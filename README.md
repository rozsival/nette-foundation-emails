# Nette Foundation for Emails

[ZURB Foundation for Emails](https://github.com/zurb/foundation-emails) with Inky markup integration into 
Nette framework including a simple mail service to send email messages generated from 
[nette/latte](https://github.com/nette/latte) templates with provided mail factory.

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
	cssFilename: 'emails.css' # default stylesheet filename located in resources directory
	resourcesDir: ./resources # default resources directory path (stylesheets to be inlined, images, etc.)
	templatesDir: ./templates # default message templates directory path
```

## Usage

The `MailFactory` has only one method called `create` to be used with your email messages. It accepts two arguments:

### `messageTemplate`

- an absolute path to your own message template
- or a path relative to the configured templates directory path (if using relative path you can omit `.latte` extension
as it will be added if needed)
- if no template is found the method throws `NetteFoundationEmails\Exceptions\MailFactoryException`

### `cssFilename` (optional)

- an absolute path to your own build of Foundation for Emails stylesheet
- or a path relative to the configured resources directory path
- if none provided, default `resources/foundation-emails.min.css` will be used

The mail factory will use any valid Nette `ITranslator` from your DI container to translate your messages.

## Mail service

You can also use the simple `NetteFoundationEmails\MailService` that ships with this package to send messages created
with the mail factory. The service gets registered with the `MailFactoryExtension` therefor it is present in your DI
container. You can use following parameters in your config to set two default properties for the service:

```neon
mailFactory:
	email: 'your@email.com' # default email to be set for messages as 'sent from'
	name: 'Your Name' # default name to be set for messages as 'sent from'
```

The service has three simple methods to `createMessage` through the `MailFactory`
(this method accepts same arguments as `create` method of `MailFactory` and only returns the resulting message),
to create a `link` to your website using Nette `LinkGenerator` and to send created `NetteFoundationEmails\Message`.
The `sendMessage` method throws `NetteFoundationEmails\Exceptions\MailServiceException` if the message could not be sent
through Nette `IMailer`.

## License

[MIT License](LICENSE)