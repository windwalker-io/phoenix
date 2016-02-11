<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Record;

use Phoenix\Utilities\AbstractPackageObjectResolver;
use Windwalker\Record\Record;

/**
 * The RecordResolver class.
 *
 * @method  static  Record  create($name, $args = array())
 * @method  static  Record  getInstance($name, $args = array(), $forceNew = false)
 *
 * @since  1.0
 */
class RecordResolver extends AbstractPackageObjectResolver
{
	/**
	 * createObject
	 *
	 * @param  string $class
	 * @param  array  $args
	 *
	 * @return Record
	 */
	protected static function createObject($class, $args = array())
	{
		if (!is_subclass_of($class, 'Windwalker\Record\Record'))
		{
			throw new \UnexpectedValueException(sprintf('Class: %s is not sub class of Windwalker\Record\Record', $class));
		}

		$db = array_shift($args);

		return new $class($db ? : static::getContainer()->get('system.db'));
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
		return ucfirst($name) . 'Record';
	}
}
