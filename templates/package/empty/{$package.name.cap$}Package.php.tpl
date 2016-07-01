<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\Language\TranslatorHelper;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Filesystem\Folder;

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

		// Language
		TranslatorHelper::loadAll($this, 'ini');
	}

	/**
	 * checkAccess
	 *
	 * @return  void
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
			$router->addRouteFromFiles(Folder::files(__DIR__ . '/Resources/routing'), $this->getName());

			// Merge other routes here...
		});

		return $router;
	}
}
