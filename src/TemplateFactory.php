<?php

namespace NetteFoundationEmails;


use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Object;

class TemplateFactory extends Object
{

	/**
	 * @var ITemplateFactory
	 */
	private $templateFactory;

	/**
	 * TemplateFactory constructor.
	 * @param ITemplateFactory $templateFactory
	 */
	public function __construct(ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}

	/**
	 * @param string $messageTemplate
	 * @return ITemplate
	 */
	public function create(string $messageTemplate): ITemplate
	{
		$template = $this->templateFactory->createTemplate();
		$template->messageTemplate = $messageTemplate;
		$template->setFile(__DIR__ . '/templates/email.latte');

		return $template;
	}

}