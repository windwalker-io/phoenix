<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\DataMapper\DataMapperResolver;
use Phoenix\Language\TranslatorHelper;
use Phoenix\Record\RecordResolver;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Filesystem\Folder;
use Windwalker\Form\FieldHelper;
use Windwalker\Form\ValidatorHelper;
use Windwalker\Router\Exception\RouteNotFoundException;

if (!defined('PACKAGE_{$package.name.upper$}_ROOT'))
{
	define('PACKAGE_{$package.name.upper$}_ROOT', __DIR__);
}

/**
 * The {$package.name.cap$}Package class.
 *
 * @since  1.0
 */
class {$package.name.cap$}Package extends AbstractPackage
{
	/**
	 * initialise
	 *
	 * @throws  \LogicException
	 * @return  void
	 */
	public function boot()
	{
		// Prepare Resolvers
		RecordResolver::addNamespace(__NAMESPACE__ . '\Record');
		DataMapperResolver::addNamespace(__NAMESPACE__ . '\DataMapper');
		FieldHelper::addNamespace(__NAMESPACE__ . '\Field');
		ValidatorHelper::addNamespace(__NAMESPACE__ . 'Validator');

		parent::boot();
	}

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->checkAccess();

		// Assets
		BootstrapScript::css();
		BootstrapScript::script();
		Asset::addCSS($this->name . '/css/{$package.name.lower$}.css');

		// Language
		TranslatorHelper::loadAll($this, 'ini');
	}

	/**
	 * checkAccess
	 *
	 * @return  void
	 *
	 * @throws  RouteNotFoundException
	 * @throws  \Exception
	 */
	protected function checkAccess()
	{

	}

	/**
	 * postExecute
	 *
	 * @param string $result
	 *
	 * @return  string
	 */
	protected function postExecute($result = null)
	{
		if (WINDWALKER_DEBUG)
		{
			if (class_exists('Windwalker\Debugger\Helper\DebuggerHelper'))
			{
				DebuggerHelper::addCustomData('Language Orphans', '<pre>' . TranslatorHelper::getFormattedOrphans() . '</pre>');
			}
		}

		return $result;
	}

	/**
	 * loadRouting
	 *
	 * @param CoreRouter $router
	 * @param string     $group
	 *
	 * @return CoreRouter
	 */
	public function loadRouting(CoreRouter $router, $group = null)
	{
		$router = parent::loadRouting($router);

		$router->group($group, function (CoreRouter $router)
		{
			$router->addRouteByConfigs(
				$router::loadRoutingFiles(Folder::files(__DIR__ . '/Resources/routing')),
				$this->getName()
			);
		});

		// Merge other routes here...

		return $router;
	}
}
