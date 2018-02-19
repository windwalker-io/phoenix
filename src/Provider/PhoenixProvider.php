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
     */
    public function register(Container $container)
    {
        if ($container->getParent()) {
            $container = $container->getParent();
        }

        if ($this->package->app->isConsole()) {
            return;
        }

        // Html document
        $closure = function (Container $container) {
            return $container->newInstance(HtmlHeaderManager::class);
        };

        $container->share(HtmlHeaderManager::class, $closure)
            ->alias('html.header', HtmlHeaderManager::class);

        $container->extend(RendererManager::class, function (RendererManager $manager, Container $container) {
            $manager->addGlobalPath(PHOENIX_SOURCE . '/Resources/templates', PriorityQueue::LOW - 25);

            return $manager;
        });
    }
}
