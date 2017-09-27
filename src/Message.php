<?php

namespace NetteFoundationEmails;


use Hampe\Inky\Inky;
use Nette\Application\UI\ITemplate;
use Nette\Localization\ITranslator;
use Nette\Mail;
use Nette\Utils\FileSystem;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Message extends Mail\Message
{

	/**
	 * @var string
	 */
	private $css;

	/**
	 * @var string
	 */
	private $resourcesDir;

	/**
	 * @var ITemplate
	 */
	private $template;

	/**
	 * @var ITranslator
	 */
	private $translator;

	/**
	 * @return string
	 */
	public function getCss(): string
	{
		return $this->css;
	}

	/**
	 * @param string $css
	 * @return Message
	 */
	public function setCss(string $css): Message
	{
		$this->css = $css;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getResourcesDir(): string
	{
		return $this->resourcesDir;
	}

	/**
	 * @param string $resourcesDir
	 * @return Message
	 */
	public function setResourcesDir(string $resourcesDir): Message
	{
		$this->resourcesDir = $resourcesDir;
		return $this;
	}

	/**
	 * @return ITemplate
	 */
	public function getTemplate(): ITemplate
	{
		return $this->template;
	}

	/**
	 * @param ITemplate $template
	 * @return Message
	 */
	public function setTemplate(ITemplate $template): Message
	{
		$this->template = $template;
		return $this;
	}

	/**
	 * @return ITranslator
	 */
	public function getTranslator(): ITranslator
	{
		return $this->translator;
	}

	/**
	 * @param ITranslator $translator
	 * @return Message
	 */
	public function setTranslator(ITranslator $translator): Message
	{
		$this->translator = $translator;
		return $this;
	}

	/**
	 * @return string
	 */
	public function generateMessage(): string
	{
		$inky = new Inky();
		$inlineStyles = new CssToInlineStyles();
		$css = FileSystem::read($this->css);
		$html = $inlineStyles->convert($inky->releaseTheKraken((string) $this->template), $css);

		$this->setHtmlBody($html, $this->resourcesDir);

		return parent::generateMessage();
	}

}