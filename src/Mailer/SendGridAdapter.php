<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Mailer;

use SendGrid\Attachment;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Personalization;
use SendGrid\Response;
use Windwalker\Core\Mailer\Adapter\MailerAdapterInterface;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Structure\Structure;

/**
 * The SendGridAdapter class.
 *
 * @since  1.3
 */
class SendGridAdapter implements MailerAdapterInterface
{
	/**
	 * Property sendgrid.
	 *
	 * @var  \SendGrid
	 */
	protected $sendgrid;

	/**
	 * SendGridAdapter constructor.
	 *
	 * @param \SendGrid $sendgrid
	 */
	public function __construct(\SendGrid $sendgrid)
	{
		$this->sendgrid = $sendgrid;
	}

	/**
	 * send
	 *
	 * @param MailMessage $message
	 *
	 * @return  boolean
	 * @throws \RuntimeException
	 */
	public function send(MailMessage $message)
	{
		$mail = new Mail;
		$personalization = new Personalization;
		$mail->setSubject($message->getSubject());

		foreach ($message->getFrom() as $email => $name)
		{
			$mail->setFrom(new Email($name, $email));
		}

		foreach ($message->getTo() as $email => $name)
		{
			$personalization->addTo(new Email($name, $email));
		}

		foreach ($message->getCc() as $email => $name)
		{
			$personalization->addCc(new Email($name, $email));
		}

		foreach ($message->getBcc() as $email => $name)
		{
			$personalization->addBcc(new Email($name, $email));
		}

		foreach ($message->getFiles() as $file)
		{
			$attach = new Attachment;

			if ($file->getFilename())
			{
				$attach->setFilename($file->getFilename());
			}

			if ($file->getContentType())
			{
				$attach->setType($file->getContentType());
			}

			$attach->setContent($file->getBody());

			$mail->addAttachment($attach);
		}

		$mail->addContent(new Content($message->getHtml() ? 'text/html' : 'text/plain', $message->getBody()));

		$mail->addPersonalization($personalization);

		/** @var Response $response */
		$response = $this->sendgrid->client->mail()->send()->post($mail);

		$body = new Structure($response->body());

		if ($response->statusCode() !== 202)
		{
			throw new \RuntimeException(
				sprintf('Error: %s - See: %s', $body['errors.0.message'], $body['errors.0.help'])
			);
		}

		return true;
	}
}
