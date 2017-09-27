<?php

namespace NetteFoundationEmails;


use Nette\Localization\ITranslator;
use Nette\Object;
use NetteFoundationEmails\DI\MailFactoryExtension;
use NetteFoundationEmails\Exceptions\MailFactoryException;

class MailFactory extends Object
{

	const TEMPLATES_EXT = '.latte';

	/**
	 * @var string
	 */
	private $cssFilename;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $resourcesDir;

	/**
	 * @var string
	 */
	private $templatesDir;

	/**
	 * @var TemplateFactory
	 */
	private $templateFactory;

	/**
	 * @var ITranslator
	 */
	private $translator;

	/**
	 * MailFactory constructor.
	 * @param array $config
	 * @param TemplateFactory $templateFactory
	 * @param ITranslator $translator
	 */
	public function __construct(array $config, TemplateFactory $templateFactory, ITranslator $translator)
	{
		if (isset($config[MailFactoryExtension::EMAIL])) {
			$this->email = $config[MailFactoryExtension::EMAIL];
		}

		if (isset($config[MailFactoryExtension::NAME])) {
			$this->name = $config[MailFactoryExtension::NAME];
		}

		$this->cssFilename = $config[MailFactoryExtension::CSS_FILENAME];
		$this->resourcesDir = $config[MailFactoryExtension::RESOURCES_DIR];
		$this->templatesDir = $config[MailFactoryExtension::TEMPLATES_DIR];
		$this->templateFactory = $templateFactory;
		$this->translator = $translator;
	}

	/**
	 * @param string $messageTemplate
	 * @param string|NULL $cssFile
	 * @return Message
	 * @throws MailFactoryException
	 */
	public function create(string $messageTemplate, string $cssFile = NULL): Message
	{
		$message = new Message();

		if (!is_file($messageTemplate)) {
			if (strpos($messageTemplate, self::TEMPLATES_EXT) === FALSE) {
				$messageTemplate .= self::TEMPLATES_EXT;
			}

			$messageTemplate = $this->templatesDir . '/' . $messageTemplate;
		}

		if (!is_file($messageTemplate)) {
			throw new MailFactoryException(
				'Email template ' . $messageTemplate . ' not found.',
				MailFactoryException::TEMPLATE_NOT_FOUND
			);
		}

		$template = $this->templateFactory->create($messageTemplate);
		$template->setTranslator($this->translator);

		if (is_file($cssFile)) {
			$css = $cssFile;
		} elseif (is_file($this->resourcesDir . '/' . $cssFile)) {
			$css = $this->resourcesDir . '/' . $cssFile;
		} elseif (is_file($this->resourcesDir . '/' . $this->cssFilename)) {
			$css = $this->resourcesDir . '/' . $this->cssFilename;
		} else {
			$css = __DIR__ . '/resources/foundation-emails.min.css';
		}

		$message->setCss($css)
			->setResourcesDir($this->resourcesDir)
			->setTemplate($template)
			->setTranslator($this->translator);

		if ($this->email) {
			$message->setFrom($this->email, $this->name);
		}

		return $message;
	}

}