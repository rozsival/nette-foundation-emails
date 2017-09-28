<?php

namespace NetteFoundationEmails\Tests;


use Mockery as m;
use Nette\Application\UI\ITemplate;
use NetteFoundationEmails\Message;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class MessageTest extends TestCase
{

	public function testGetResourcesDir()
	{
		$message = new Message();
		$message->setResourcesDir(__DIR__);
		Assert::equal(__DIR__, $message->getResourcesDir());
	}

	public function testGenerateMessage()
	{
		$template = m::mock(ITemplate::class);
		$message = new Message();
		$message->setCss(__DIR__ . '/resources/resource.css')
			->setResourcesDir(__DIR__ . '/resources')
			->setTemplate($template);

		Assert::type('string', $message->generateMessage());
	}

}

$test = new MessageTest();
$test->run();