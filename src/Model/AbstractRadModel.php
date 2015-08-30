<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Record\Record;

/**
 * The AbstractRadModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractRadModel extends DatabaseModel
{
	/**
	 * getRecord
	 *
	 * @param   string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null)
	{
		$name = $name ? : $this->getName();

		$class = sprintf('%s\Record\%sRecord', MvcHelper::getPackageNamespace(get_called_class(), 2), ucfirst($name));

		if (!class_exists($class))
		{
			throw new \DomainException($class . ' not exists.');
		}

		return new $class($this->db);
	}

	/**
	 * getDataMapper
	 *
	 * @param string $name
	 *
	 * @return  DataMapper
	 */
	public function getDataMapper($name = null)
	{
		$name = $name ? : $this->getName();

		$class = sprintf('%s\DataMapper\%sMapper', MvcHelper::getPackageNamespace(get_called_class(), 2), ucfirst($name));

		if (!class_exists($class))
		{
			throw new \DomainException($class . ' not exists.');
		}

		return new $class($this->db);
	}
}
