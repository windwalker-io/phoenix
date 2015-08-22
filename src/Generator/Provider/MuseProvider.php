<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Provider;

use Muse\Windwalker\IO;
use Phoenix\Generator\FileOperator\OperatorFactory;
use Windwalker\Console\Command\Command;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The MuseProvider class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class MuseProvider implements ServiceProviderInterface
{
	/**
	 * Property command.
	 *
	 * @var Command
	 */
	protected $command;

	/**
	 * Constructor.
	 *
	 * @param Command $command
	 */
	public function __construct(Command $command)
	{
		$this->command = $command;
	}

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$ioClass = 'Phoenix\IO\IO';

		$container->alias('io', $ioClass)
			->alias('Muse\IO\IO', $ioClass)
			->alias('Muse\IO\IOInterface', $ioClass)
			->share($ioClass, new IO($this->command));

		$container->alias('operator.copy', 'Phoenix\Generaotr\FileOperator\CopyOperator')
			->createObject('Phoenix\Generaotr\FileOperator\CopyOperator');

		$closure = function(Container $container)
		{
			return new OperatorFactory($container->get('io'), array('{{', '}}'));
		};

		$container->share('operator.factory', $closure);
	}
}
