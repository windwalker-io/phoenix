<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Phoenix\DataMapper\DataMapperResolver;
use Phoenix\Record\RecordResolver;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Record\Record;

/**
 * The AbstractRadModel class.
 *
 * @since  1.0
 */
class PhoenixModel extends DatabaseModel
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

		$record = RecordResolver::create($name, array($this->db));

		if ($record)
		{
			return $record;
		}

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

		$mapper = DataMapperResolver::create($name, array($this->db));

		if ($mapper)
		{
			return $mapper;
		}

		$class = sprintf('%s\DataMapper\%sMapper', MvcHelper::getPackageNamespace(get_called_class(), 2), ucfirst($name));

		if (!class_exists($class))
		{
			throw new \DomainException($class . ' not exists.');
		}

		return new $class($this->db);
	}
}
