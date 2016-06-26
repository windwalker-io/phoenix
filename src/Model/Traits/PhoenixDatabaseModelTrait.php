<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model\Traits;

use Phoenix\DataMapper\DataMapperResolver;
use Phoenix\Record\RecordResolver;
use Windwalker\Core\DataMapper\AbstractDataMapperProxy;
use Windwalker\Core\Model\Traits\DatabaseModelTrait;
use Windwalker\Core\Mvc\MvcHelper;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Record\Record;

/**
 * The PhoenixModelTrait class.
 *
 * @since  {DEPLOY_VERSION}
 */
trait PhoenixDatabaseModelTrait
{
	use DatabaseModelTrait;

	/**
	 * Default Record name
	 *
	 * @var  string
	 */
	protected $record;

	/**
	 * Default DataMapper name.
	 *
	 * @var  string
	 */
	protected $dataMapper;
	
	/**
	 * getRecord
	 *
	 * @param   string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null)
	{
		$name = $name ? : $this->record;
		$name = $name ? : $this->getName();

		$mapper = $this->getDataMapper();

		if ($mapper instanceof AbstractDataMapperProxy)
		{
			$mapper = $mapper->getInstance();
		}

		$record = RecordResolver::create($name, null, 'id', $mapper);

		if ($record)
		{
			return $record;
		}

		$class = sprintf('%s\Record\%sRecord', MvcHelper::getPackageNamespace(get_called_class(), 2), ucfirst($name));

		if (!class_exists($class))
		{
			throw new \DomainException($class . ' not exists.');
		}

		return new $class;
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
		$name = $name ? : $this->dataMapper;
		$name = $name ? : $this->getName();

		$mapper = DataMapperResolver::create($name);

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
