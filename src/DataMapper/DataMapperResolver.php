<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\DataMapper;

use Phoenix\Utilities\AbstractPackageObjectResolver;
use Windwalker\DataMapper\DataMapper;

/**
 * The DataMapperResolver class.
 *
 * @method  static  DataMapper  create($name, $args = array())
 * @method  static  DataMapper  getInstance($name, $args = array(), $forceNew = false)
 *
 * @since  1.0
 */
abstract class DataMapperResolver extends AbstractPackageObjectResolver
{
	/**
	 * createObject
	 *
	 * @param  string $class
	 * @param  array  $args
	 *
	 * @return string
	 */
	protected static function createObject($class, $args = array())
	{
		if (!is_subclass_of($class, 'Lyrasoft\Luna\Module\AbstractModule'))
		{
			throw new \UnexpectedValueException(sprintf('Class: %s is not vu class of Lyrasoft\Luna\Module\AbstractModule', $class));
		}

		return $class;
	}

	/**
	 * getClass
	 *
	 * @param string $name
	 *
	 * @return  string
	 */
	public static function getClass($name)
	{
		return ucfirst($name) . '\\' . ucfirst($name) . 'Module';
	}
}
