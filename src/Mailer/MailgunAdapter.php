<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Mailer;

use Mailgun\Mailgun;
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
     */
    public function send(MailMessage $message)
    {
        $params            = [];
        $params['subject'] = $message->getSubject();

        $message->getHtml() ? $params['html'] = $message->getBody() : $params['text'] = $message->getBody();

        foreach ($message->getFrom() as $email => $name) {
            $params['from'][] = sprintf('%s <%s>', $name, $email);
        }

        foreach ($message->getTo() as $email => $name) {
            $params['to'][] = sprintf('%s <%s>', $name, $email);
        }

        foreach ($message->getCc() as $email => $name) {
            $params['cc'][] = sprintf('%s <%s>', $name, $email);
        }

        foreach ($message->getBcc() as $email => $name) {
            $params['bcc'][] = sprintf('%s <%s>', $name, $email);
        }

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
