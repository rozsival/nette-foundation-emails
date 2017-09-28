<?php

namespace NetteFoundationEmails;


use Nette\Application\LinkGenerator;
use Nette\Mail\IMailer;
use Nette\Mail\SendException;
use Nette\Object;
use NetteFoundationEmails\Exceptions\MailServiceException;

class MailService extends Object
{

	/**
	 * @var LinkGenerator
	 */
	private $linkGenerator;

	/**
	 * @var MailFactory
	 */
	private $mailFactory;

	/**
	 * @var IMailer
	 */
	private $mailer;

	/**
	 * MailService constructor.
	 * @param LinkGenerator $linkGenerator
	 * @param MailFactory $mailFactory
	 * @param IMailer $mailer
	 */
	public function __construct(LinkGenerator $linkGenerator, MailFactory $mailFactory, IMailer $mailer)
	{
		$this->linkGenerator = $linkGenerator;
		$this->mailFactory = $mailFactory;
		$this->mailer = $mailer;
	}

	/**
	 * @param string $dest
	 * @param array $params
	 * @return string
	 */
	public function link(string $dest, array $params = []): string
	{
		return $this->linkGenerator->link($dest, $params);
	}

	/**
	 * @param string $messageTemplate
	 * @param string|NULL $cssFile
	 * @return Message
	 */
	public function createMessage(string $messageTemplate, string $cssFile = NULL): Message
	{
		return $this->mailFactory->create($messageTemplate, $cssFile);
	}

	/**
	 * @param Message $mail
	 * @throws MailServiceException
	 */
	public function sendMessage(Message $mail)
	{
		try {
			$this->mailer->send($mail);
		} catch (SendException $sendException) {
			throw new MailServiceException($sendException->getMessage(), MailServiceException::MAIL_NOT_SENT);
		}
	}

}