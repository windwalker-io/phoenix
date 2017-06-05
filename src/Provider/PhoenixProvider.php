<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Provider;

use Phoenix\Html\HtmlHeaderManager;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Renderer\RendererManager;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
use Windwalker\Filesystem\Folder;
use Windwalker\String\StringHelper;
use Windwalker\Utilities\Queue\PriorityQueue;

/**
 * The AssetProvider class.
 * 
 * @since  1.0
 */
class PhoenixProvider implements ServiceProviderInterface
{
	/**
	 * Property package.
	 *
	 * @var  AbstractPackage
	 */
	protected $package;

	/**
	 * PhoenixProvider constructor.
	 *
	 * @param AbstractPackage $package
	 */
	public function __construct(AbstractPackage $package)
	{
		$this->package = $package;
	}

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  void
	 * @throws \InvalidArgumentException
	 */
	public function register(Container $container)
	{
		if ($container->getParent())
		{
			$container = $container->getParent();
		}

		if ($this->package->app->isConsole())
		{
			return;
		}

		// Html document
		$closure = function(Container $container)
		{
			return $container->newInstance(HtmlHeaderManager::class);
		};

		$container->share(HtmlHeaderManager::class, $closure)
			->alias('html.header', HtmlHeaderManager::class);

		$container->extend(RendererManager::class, function (RendererManager $manager, Container $container)
		{
		    $manager->addGlobalPath(PHOENIX_SOURCE . '/Resources/templates', PriorityQueue::LOW - 25);

			return $manager;
		});

		$this->registerClassAlias();
	}

	/**
	 * registerClassAlias
	 *
	 * @return  void
	 */
	protected function registerClassAlias()
	{
		// Model to Repository
		class_alias('Phoenix\Repository\AdminRepository', 'Phoenix\Model\AdminModel');
		class_alias('Phoenix\Repository\AdminRepositoryInterface', 'Phoenix\Model\AdminRepositoryInterface');
		class_alias('Phoenix\Repository\CrudRepository', 'Phoenix\Model\CrudModel');
		class_alias('Phoenix\Repository\CrudRepositoryInterface', 'Phoenix\Model\CrudRepositoryInterface');
		class_alias('Phoenix\Repository\Filter\AbstractFilterHelper', 'Phoenix\Model\Filter\AbstractFilterHelper');
		class_alias('Phoenix\Repository\Filter\FilterHelper', 'Phoenix\Model\Filter\FilterHelper');
		class_alias('Phoenix\Repository\Filter\FilterHelperInterface', 'Phoenix\Model\Filter\FilterHelperInterface');
		class_alias('Phoenix\Repository\Filter\SearchHelper', 'Phoenix\Model\Filter\SearchHelper');
		class_alias('Phoenix\Repository\FormAwareRepositoryInterface', 'Phoenix\Model\FormAwareRepositoryInterface');
		class_alias('Phoenix\Repository\ItemRepository', 'Phoenix\Model\ItemModel');
		class_alias('Phoenix\Repository\ListRepository', 'Phoenix\Model\ListModel');
		class_alias('Phoenix\Repository\ListRepositoryInterface', 'Phoenix\Model\ListRepositoryInterface');
		class_alias('Phoenix\Repository\NestedAdminRepository', 'Phoenix\Model\NestedAdminModel');
		class_alias('Phoenix\Repository\Traits\FormAwareRepositoryTrait', 'Phoenix\Model\Traits\FormAwareRepositoryTrait');
	}
}
