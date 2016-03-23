<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Provider;

use Phoenix\Asset\AssetManager;
use Phoenix\Html\HtmlHeaderManager;
use Windwalker\Core\Console\WindwalkerConsole;
use Windwalker\Core\Renderer\RendererFactory;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Renderer\Blade\GlobalContainer;
use Windwalker\Utilities\Queue\Priority;

/**
 * The AssetProvider class.
 * 
 * @since  1.0
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

		if ($container->get('app') instanceof WindwalkerConsole)
		{
			return;
		}

		// Html document
		$closure = function(Container $container)
		{
			return new HtmlHeaderManager;
		};

		$container->share('phoenix.html.header', $closure)->alias('phoenix.document', 'phoenix.html.header');

		// Asset
		$closure = function(Container $container)
		{
			return new AssetManager;
		};

		$container->share('phoenix.asset', $closure);

		/** @var RendererFactory $factory */
		$factory = $container->get('renderer.factory');

		$factory->addGlobalPath(PHOENIX_SOURCE . '/Resources/templates', Priority::LOW - 25);

		// Register Blade directive
		GlobalContainer::addCompiler('assetTemplate', function($expression)
		{
			return "<?php \\Phoenix\\Asset\\Asset::getTemplate()->startTemplate{$expression} ?>";
		});

		GlobalContainer::addCompiler('endTemplate', function($expression)
		{
			return "<?php \\Phoenix\\Asset\\Asset::getTemplate()->endTemplate{$expression} ?>";
		});
	}
}
