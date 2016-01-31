<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Provider;

use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Registry\Registry;

/**
 * The SwiftMailerProvider class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SwiftMailerProvider implements ServiceProviderInterface
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
		$closure = function(Container $container)
		{
			$config = $container->get('system.config');

			$transport = $this->createSwiftTransport($config->get('mail.transport'), $config);

			return \Swift_Mailer::newInstance($transport);
		};

		$container->getParent()->share('phoenix.swiftmailer', $closure);
	}

	/**
	 * createSwiftTransport
	 *
	 * @param  string   $transport
	 * @param  Registry $config
	 *
	 * @return \Swift_Transport
	 */
	protected function createSwiftTransport($transport, $config)
	{
		switch ($transport)
		{
			case 'smtp':

				$instance = \Swift_SmtpTransport::newInstance(
						$config->get('mail.host'),
						$config->get('mail.port', 465),
						$config->get('mail.security', 'ssl')
					)->setUsername($config->get('mail.username'))
					->setPassword($config->get('mail.password'));

				break;

			case 'sendmail':

				$instance = \Swift_SendmailTransport::newInstance();

				break;

			default:
				$instance = \Swift_MailTransport::newInstance();
		}

		return $instance;
	}
}
