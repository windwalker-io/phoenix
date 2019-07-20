<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Mailer;

use Mailgun\Mailgun;
use Mailgun\Message\MessageBuilder;
use Windwalker\Core\Config\Config;
use Windwalker\Core\Mailer\Adapter\MailerAdapterInterface;
use Windwalker\Core\Mailer\MailMessage;

/**
 * The MailgunAdapter class.
 *
 * @since  1.3
 */
class MailgunAdapter implements MailerAdapterInterface
{
    /**
     * Property Mailgun.
     *
     * @var  Mailgun
     */
    protected $mailgun;

    /**
     * Property config.
     *
     * @var  Config
     */
    protected $config;

    /**
     * SendGridAdapter constructor.
     *
     * @param Mailgun $mailgun
     */
    public function __construct(Mailgun $mailgun, Config $config)
    {
        $this->mailgun = $mailgun;
        $this->config  = $config;
    }

    /**
     * send
     *
     * @param MailMessage $message
     *
     * @return  boolean
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Mailgun\Message\Exceptions\TooManyRecipients
     */
    public function send(MailMessage $message)
    {
        $params            = [];
        $params['subject'] = $message->getSubject();

        $builder = new MessageBuilder();

        $message->getHtml() ? $builder->setHtmlBody($message->getBody()) : $builder->setTextBody($message->getBody());

        foreach ($message->getFrom() as $email => $name) {
            $builder->setFromAddress($email, ['full_name' => $name]);
        }

        foreach ($message->getTo() as $email => $name) {
            $builder->addToRecipient($email, ['full_name' => $name]);
        }

        foreach ($message->getCc() as $email => $name) {
            $builder->addCcRecipient($email, ['full_name' => $name]);
        }

        foreach ($message->getBcc() as $email => $name) {
            $builder->addBccRecipient($email, ['full_name' => $name]);
        }

        foreach ($message->getReplyTo() as $email => $name) {
            $builder->setReplyToAddress($email, ['full_name' => $name]);
        }

        $params = $builder->getMessage();

        foreach ($message->getFiles() as $file) {
            $attach = [];

            if ($file->getFilename()) {
                $attach['filename'] = $file->getFilename();
            }

            if ($file->getContentType()) {
                $attach['contentType'] = $file->getContentType();
            }

            $attach['fileContent'] = $file->getBody();

            $params['attachment'][] = $attach;
        }

        $domain = $this->config->get('mail.mailgun.domain');

        if (!$domain) {
            throw new \InvalidArgumentException('Config mail.mailgun.domain should not be empty.');
        }

        $this->mailgun->messages()->send($domain, $params);

        return true;
    }
}
