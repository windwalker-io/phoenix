<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\String\StringNormalise;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The ControllerResolver class.
 *
 * @since  1.0
 */
class ControllerResolver extends \Windwalker\Core\Controller\ControllerResolver
{
	/**
	 * getController
	 *
	 * @param   AbstractPackage|string $package
	 * @param   string                 $controller
	 * @param   string                 $name
	 *
	 * @return  mixed
	 */
	public static function getController($package, $controller, $name = null)
	{
		$controller = str_replace(array('.', '/'), '\\', $controller);
		$controller = StringNormalise::toClassNamespace($controller);

		try
		{
			$class = parent::getController($package, $controller);
		}
		catch (\UnexpectedValueException $e)
		{
			if (!$package instanceof AbstractPackage)
			{
				$package = PackageHelper::getPackage($package);
			}

			$class = sprintf('%s\Controller\%s\%sController', ReflectionHelper::getNamespaceName($package), ucfirst($name), $controller);

			if (!class_exists($class))
			{
				// $class = sprintf('Phoenix\Controller\%sController', $controller);
			}

			if (!class_exists($class))
			{
				throw new \UnexpectedValueException('Controller: ' . $class . ' not found.', 404, $e);
			}
		}

		return $class;
	}
}
