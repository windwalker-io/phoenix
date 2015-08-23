<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Provider;

use Phoenix\Asset\AssetManager;
use Phoenix\Html\DocumentManager;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;

/**
 * The AssetProvider class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class PhoenixProvider implements ServiceProviderInterface
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
		if ($container->getParent())
		{
			$container = $container->getParent();
		}

		// Html document
		$closure = function(Container $container)
		{
			return new DocumentManager;
		};

		$container->share('phoenix.document', $closure);

		// Asset
		$closure = function(Container $container)
		{
			return new AssetManager;
		};

		$container->share('phoenix.asset', $closure);
	}
}
