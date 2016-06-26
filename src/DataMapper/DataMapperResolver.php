<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\DataMapper;

use Phoenix\Utilities\AbstractPackageObjectResolver;
use Windwalker\Core\DataMapper\AbstractDataMapperProxy;
use Windwalker\DataMapper\DataMapper;

/**
 * The DataMapperResolver class.
 *
 * @method  static  DataMapper  create($name, ...$args)
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
	 * @return DataMapper
	 */
	protected static function createObject($class, ...$args)
	{
		if (!is_subclass_of($class, DataMapper::class) && !is_subclass_of($class, AbstractDataMapperProxy::class))
		{
			throw new \UnexpectedValueException(sprintf('Class: %s is not sub class of ' . DataMapper::class, $class));
		}

		return new $class(...$args);
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
		return ucfirst($name) . 'Mapper';
	}
}
