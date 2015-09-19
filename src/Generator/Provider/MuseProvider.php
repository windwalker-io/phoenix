<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Provider;

use Muse\Windwalker\IO;
use Phoenix\Generator\Controller\GeneratorController;
use Phoenix\Generator\FileOperator\OperatorFactory;
use Windwalker\Console\Command\Command;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The MuseProvider class.
 * 
 * @since  1.0
 */
class MuseProvider implements ServiceProviderInterface
{
	/**
	 * Property command.
	 *
	 * @var Command
	 */
	protected $controller;

	/**
	 * Constructor.
	 *
	 * @param GeneratorController $controller
	 */
	public function __construct(GeneratorController $controller)
	{
		$this->controller = $controller;
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
			->share($ioClass, new IO($this->controller->getCommand()));

		$container->alias('operator.copy', 'Phoenix\Generaotr\FileOperator\CopyOperator')
			->createObject('Phoenix\Generaotr\FileOperator\CopyOperator');

		$closure = function(Container $container)
		{
			return new OperatorFactory($container->get('io'), $this->controller->getTagVariables());
		};

		$container->share('operator.factory', $closure);
	}
}
