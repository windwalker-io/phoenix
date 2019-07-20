<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Provider;

use Phoenix\Breadcrumb\BreadcrumbManager;
use Phoenix\Html\HtmlHeaderManager;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Renderer\RendererManager;
use Windwalker\Core\Security\CsrfGuard;
use Windwalker\DI\Container;
use Windwalker\DI\ServiceProviderInterface;
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
     * @param Container $container The DI container.
     *
     * @return  void
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function register(Container $container)
    {
        $this->registerClassAlias();

        if ($container->getParent()) {
            $container = $container->getParent();
        }

        $container->registerServiceProvider($container->newInstance(SendgridProvider::class));
        $container->registerServiceProvider($container->newInstance(MailgunProvider::class));

        if ($this->package->app->isConsole()) {
            return;
        }

        // Html document
        $closure = function (Container $container) {
            return $container->newInstance(HtmlHeaderManager::class);
        };

        $container->share(HtmlHeaderManager::class, $closure)
            ->alias('html.header', HtmlHeaderManager::class);

        $container->prepareSharedObject(BreadcrumbManager::class)
            ->alias('breadcrumb', BreadcrumbManager::class);

        $container->extend(RendererManager::class, static function (RendererManager $manager, Container $container) {
            $manager->addGlobalPath(PHOENIX_SOURCE . '/Resources/templates', PriorityQueue::LOW - 25);

            return $manager;
        });

        $container->extend(CsrfGuard::class, static function (CsrfGuard $guard) {
            $guard->setMessage(__('phoenix.message.invalid.token'));

            return $guard;
        });
    }

    /**
     * registerClassAlias
     *
     * @return  void
     *
     * @since  1.6
     */
    protected function registerClassAlias()
    {
        static $registered = false;

        if ($registered) {
            return;
        }

        class_alias(\Phoenix\Repository\AdminRepository::class, \Phoenix\Model\AdminModel::class);
        class_alias(\Phoenix\Repository\AdminRepositoryInterface::class, \Phoenix\Model\AdminRepositoryInterface::class);
        class_alias(\Phoenix\Repository\CrudRepository::class, \Phoenix\Model\CrudModel::class);
        class_alias(\Phoenix\Repository\CrudRepositoryInterface::class, \Phoenix\Model\CrudRepositoryInterface::class);
        class_alias(\Phoenix\Repository\FormAwareRepositoryInterface::class, \Phoenix\Model\FormAwareRepositoryInterface::class);
        class_alias(\Phoenix\Repository\ItemRepository::class, \Phoenix\Model\ItemModel::class);
        class_alias(\Phoenix\Repository\ListRepository::class, \Phoenix\Model\ListModel::class);
        class_alias(\Phoenix\Repository\ListRepositoryInterface::class, \Phoenix\Model\ListRepositoryInterface::class);
        class_alias(\Phoenix\Repository\NestedAdminRepository::class, \Phoenix\Model\NestedAdminModel::class);
        class_alias(\Phoenix\Repository\Filter\AbstractFilterHelper::class, \Phoenix\Model\Filter\AbstractFilterHelper::class);
        class_alias(\Phoenix\Repository\Filter\FilterHelper::class, \Phoenix\Model\Filter\FilterHelper::class);
        class_alias(\Phoenix\Repository\Filter\FilterHelperInterface::class, \Phoenix\Model\Filter\FilterHelperInterface::class);
        class_alias(\Phoenix\Repository\Filter\SearchHelper::class, \Phoenix\Model\Filter\SearchHelper::class);
        class_alias(\Phoenix\Repository\Traits\FormAwareRepositoryTrait::class, \Phoenix\Model\Traits\FormAwareRepositoryTrait::class);

        $registered = true;
    }
}
