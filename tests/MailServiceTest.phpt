<?php

namespace NetteFoundationEmails\Tests;


use Mockery as m;
use Nette\Application\LinkGenerator;
use Nette\Mail\IMailer;
use Nette\Mail\SendException;
use NetteFoundationEmails\Exceptions\MailServiceException;
use NetteFoundationEmails\MailFactory;
use NetteFoundationEmails\MailService;
use NetteFoundationEmails\Message;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class MailServiceTest extends TestCase
{

	public function testGenerateLink()
	{
		$mailService = $this->createMailService();
		Assert::equal('http://domain.com', $mailService->link('dest'));
	}

	public function testCreateMessage()
	{
		$mailService = $this->createMailService();
		Assert::equal(new Message(), $mailService->createMessage('message'));
	}

	public function testSendMessage()
	{
		$mailer = $this->createMailer();
		$mailer->shouldReceive('send')
			->andReturnNull();

		$mailService = new MailService($this->createLinkGenerator(), $this->createMailFactory(), $mailer);
		Assert::equal(NULL, $mailService->sendMessage(new Message()));
	}

	public function testSendMessageFailure()
	{
		Assert::exception(function () {
			$mailer = $this->createMailer();
			$mailer->shouldReceive('send')
				->andThrow(SendException::class);

			$mailService = new MailService($this->createLinkGenerator(), $this->createMailFactory(), $mailer);
			$mailService->sendMessage(new Message());
		},
			MailServiceException::class,
			NULL,
			MailServiceException::MAIL_NOT_SENT
		);
	}

	private function createMailService()
	{
		return new MailService($this->createLinkGenerator(), $this->createMailFactory(), $this->createMailer());
	}

	private function createLinkGenerator()
	{
		$linkGenerator = m::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->andReturn('http://domain.com');

		return $linkGenerator;
	}

	private function createMailFactory()
	{
		$mailFactory = m::mock(MailFactory::class);
		$mailFactory->shouldReceive('create')
			->andReturn(new Message());

		return $mailFactory;
	}

	private function createMailer()
	{
		return m::mock(IMailer::class);
	}

}

$test = new MailServiceTest();
$test->run();