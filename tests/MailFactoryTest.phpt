<?php

namespace NetteFoundationEmails\Tests;


use Mockery as m;
use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Localization\ITranslator;
use NetteFoundationEmails\DI\MailFactoryExtension;
use NetteFoundationEmails\Exceptions\MailFactoryException;
use NetteFoundationEmails\MailFactory;
use NetteFoundationEmails\Message;
use NetteFoundationEmails\TemplateFactory;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class MailFactoryTest extends TestCase
{

	const EMAIL = 'email@domain.com';
	const NAME = 'Jožko Kukuricúdus';
	const DEFAULT_CSS = 'emails.css';
	const FALLBACK_CSS = 'foundation-emails.min.css';
	const TEMPLATES_DIR = __DIR__ . '/templates';
	const RESOURCES_DIR = __DIR__ . '/resources';

	public function testMessageWithCustomCssPathCreated()
	{
		$mailFactory = $this->createMailFactory(
			$this->createTemplateFactory(),
			$this->createTranslator(),
			self::RESOURCES_DIR
		);
		$expectedMessage = $this->createMessage('template.latte', self::RESOURCES_DIR . '/resource.css');
		$message = $mailFactory->create('template', self::RESOURCES_DIR . '/resource.css');
		$this->assertMessage($expectedMessage, $message);
	}

	public function testMessageWithCssFoundInResourcesDirCreated()
	{
		$mailFactory = $this->createMailFactory(
			$this->createTemplateFactory(),
			$this->createTranslator(),
			self::RESOURCES_DIR
		);
		$expectedMessage = $this->createMessage('template.latte', self::RESOURCES_DIR . '/resource.css');
		$message = $mailFactory->create('template', 'resource.css');
		$this->assertMessage($expectedMessage, $message);
	}

	public function testMessageWithDefaultCssCreated()
	{
		$mailFactory = $this->createMailFactory(
			$this->createTemplateFactory(),
			$this->createTranslator(),
			self::RESOURCES_DIR . '/default'
		);

		$expectedMessage = $this->createMessage(
			'template.latte',
			self::RESOURCES_DIR . '/default/' . self::DEFAULT_CSS)
		;

		$message = $mailFactory->create('template');
		$this->assertMessage($expectedMessage, $message);
	}

	public function testMessageWithFallBackCssCreated()
	{
		$mailFactory = $this->createMailFactory(
			$this->createTemplateFactory(),
			$this->createTranslator(),
			self::RESOURCES_DIR
		);

		$expectedMessage = $this->createMessage('template.latte');
		$message = $mailFactory->create('template', __DIR__ . '/test.css');
		$this->assertMessage($expectedMessage, $message);
	}

	public function testTemplateNotFound()
	{
		$template = 'test.latte';
		$mailFactory = $this->createMailFactory(
			$this->createTemplateFactory(),
			$this->createTranslator(),
			self::RESOURCES_DIR
		);

		Assert::exception(
			function () use ($template, $mailFactory) {
				$mailFactory->create($template);
			},
			MailFactoryException::class,
			NULL,
			MailFactoryException::TEMPLATE_NOT_FOUND
		);
	}

	private function assertMessage(Message $expectedMessage, Message $message)
	{
		Assert::equal(basename($expectedMessage->getCss()), basename($message->getCss()));
		Assert::equal($expectedMessage->getFrom(), $message->getFrom());
		Assert::equal($expectedMessage->getTemplate()->getFile(), $message->getTemplate()->getFile());
		Assert::equal($expectedMessage->getBody(), $message->getBody());
		Assert::equal($expectedMessage->getHtmlBody(), $message->getHtmlBody());
		Assert::equal($expectedMessage->getTranslator(), $message->getTranslator());
	}

	private function createMailFactory($templateFactory, $translator, $resourcesDir)
	{
		$mailFactory = new MailFactory(
			[
				MailFactoryExtension::EMAIL => self::EMAIL,
				MailFactoryExtension::NAME => self::NAME,
				MailFactoryExtension::CSS_FILENAME => self::DEFAULT_CSS,
				MailFactoryExtension::TEMPLATES_DIR => self::TEMPLATES_DIR,
				MailFactoryExtension::RESOURCES_DIR => $resourcesDir
			],
			$templateFactory,
			$translator
		);

		return $mailFactory;
	}

	private function createMessage($template, $css = NULL)
	{
		$templateFactory = $this->createTemplateFactory();
		$message = new Message();
		$message->setResourcesDir(self::RESOURCES_DIR)
			->setCss($css ?: self::FALLBACK_CSS)
			->setTranslator($this->createTranslator())
			->setTemplate($templateFactory->create(self::TEMPLATES_DIR . '/' . $template))
			->setFrom(self::EMAIL, self::NAME);

		return $message;
	}

	private function createTemplateFactory()
	{
		$template = m::mock(ITemplate::class);
		$template->shouldReceive('setFile');
		$template->shouldReceive('setTranslator');
		$template->shouldReceive('getFile')
			->andReturn('template.latte');

		$templateFactory = m::mock(ITemplateFactory::class);
		$templateFactory->shouldReceive('createTemplate')
			->andReturn($template);

		return new TemplateFactory($templateFactory);
	}

	private function createTranslator()
	{
		return m::mock(ITranslator::class);
	}

}

$test = new MailFactoryTest();
$test->run();