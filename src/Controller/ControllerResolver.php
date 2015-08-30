<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Core\Package\AbstractPackage;
use Windwalker\String\StringNormalise;

/**
 * The ControllerResolver class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ControllerResolver extends \Windwalker\Core\Controller\ControllerResolver
{
	/**
	 * getController
	 *
	 * @param   AbstractPackage|string  $package
	 * @param   string                  $controller
	 *
	 * @return  mixed
	 */
	public static function getController($package, $controller)
	{
		$controller = str_replace(array('.', '/'), '\\', $controller);
		$controller = StringNormalise::toClassNamespace($controller);

		try
		{
			$class = parent::getController($package, $controller);
		}
		catch (\UnexpectedValueException $e)
		{
			$class = sprintf('%s\%sController', __NAMESPACE__, ucfirst($controller));

			if (!class_exists($class))
			{
				$class = sprintf('Phoenix\Controller\%sController', ucfirst($controller));
			}

			if (!class_exists($class))
			{
				throw new \UnexpectedValueException('Controller: ' . $class . ' not found.', 404, $e);
			}
		}

		return $class;
	}
}
