<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Provider;

use Mailgun\Mailgun;
use Phoenix\Mailer\MailgunAdapter;
use Windwalker\Core\Config\Config;
use Windwalker\Core\Mailer\Adapter\MailerAdapterInterface;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The MailGunProvider class.
 *
 * @since  1.3
 */
class MailgunProvider implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container $container The DI container.
     *
     * @return  void
     */
    public function register(Container $container)
    {
        $container->share(Mailgun::class, [$this, 'mailgun'])
            ->alias('mailgun', Mailgun::class);

        $container->prepareSharedObject(MailgunAdapter::class)
            ->alias('mailer.adapter.mailgun', MailgunAdapter::class);
    }

    /**
     * swiftmailer
     *
     * @param Container $container
     *
     * @return  Mailgun
     *
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function mailgun(Container $container)
    {
        if (!class_exists(Mailgun::class)) {
            throw new \LogicException('Please install mailgun/mailgun-php kriswallsmith/buzz nyholm/psr7 first.');
        }

        /** @var Config $config */
        $config = $container->get('config');

        return Mailgun::create($config->get('mail.mailgun.key'));
    }
}
