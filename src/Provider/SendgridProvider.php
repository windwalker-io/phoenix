<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Provider;

use Phoenix\Mailer\SendGridAdapter;
use Windwalker\Core\Config\Config;
use Windwalker\Core\Mailer\Adapter\MailerAdapterInterface;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The SendgridProvider class.
 *
 * @since  __DEPLOY_VERSION__
 */
class SendgridProvider implements ServiceProviderInterface
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
		$container->share(\SendGrid::class, [$this, 'sendgrid'])
			->alias('sendgrid', \SendGrid::class);

		$container->prepareSharedObject(SendGridAdapter::class)
			->alias('mailer.adapter.swiftmailer', SendGridAdapter::class)
			->alias(MailerAdapterInterface::class, SendGridAdapter::class);
	}

	/**
	 * swiftmailer
	 *
	 * @param Container $container
	 *
	 * @return  \SendGrid
	 *
	 * @throws \UnexpectedValueException
	 * @throws \LogicException
	 */
	public function sendgrid(Container $container)
	{
		if (!class_exists(\SendGrid::class))
		{
			throw new \LogicException('Please install sendgrid/sendgrid 5.* first.');
		}

		/** @var Config $config */
		$config = $container->get('config');

		return new \SendGrid($config->get('mail.sendgrid.key'));
	}
}
