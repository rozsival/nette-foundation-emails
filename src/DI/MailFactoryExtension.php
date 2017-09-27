<?php

namespace NetteFoundationEmails\DI;


use Nette\DI\CompilerExtension;
use NetteFoundationEmails\MailFactory;

class MailFactoryExtension extends CompilerExtension
{

	const CSS_FILENAME = 'cssFilename';
	const EMAIL = 'email';
	const NAME = 'name';
	const RESOURCES_DIR = 'resourcesDir';
	const TEMPLATES_DIR = 'templatesDir';

	/**
	 * @var array
	 */
	private $defaults = [
		self::CSS_FILENAME => 'emails.css',
		self::RESOURCES_DIR => __DIR__ . '/../resources',
		self::TEMPLATES_DIR => __DIR__ . '/../templates'
	];

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();
		$builder->addDefinition($this->prefix('netteFoundationEmails'))
			->setFactory(MailFactory::class, [
				'config' => $config
			]);

		$this->compiler->loadDefinitions(
			$builder,
			$this->loadFromFile(__DIR__ . '/../config/services.neon')['services'],
			$this->name
		);
	}

}