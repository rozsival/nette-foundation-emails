<?php

namespace NetteFoundationEmails\Tests;


use Nette\DI\Compiler;
use Nette\DI\ContainerBuilder;
use NetteFoundationEmails\DI\MailFactoryExtension;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/bootstrap.php';

class MailFactoryExtensionTest extends TestCase
{

	public function testLoadConfiguration()
	{
		$builder = new ContainerBuilder();
		$compiler = new Compiler($builder);
		$extension = new MailFactoryExtension();
		$extension->setCompiler($compiler, 'test');
		Assert::equal(NULL, $extension->loadConfiguration());
	}

}

$test = new MailFactoryExtensionTest();
$test->run();