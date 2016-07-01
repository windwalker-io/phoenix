<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Phoenix\Model\Traits\FormAwareRepositoryTrait;
use Windwalker\Core\Model\ModelRepositoryInterface;
use Windwalker\Data\Data;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Record\Record;

/**
 * The AbstractCrudModel class.
 *
 * @since  1.0
 */
class CrudModel extends ItemModel implements FormAwareRepositoryInterface, CrudRepositoryInterface
{
	use FormAwareRepositoryTrait;

	const ORDER_POSITION_FIRST = 'first';
	const ORDER_POSITION_LAST  = 'last';
	
	/**
	 * Property updateNulls.
	 *
	 * @var  boolean
	 */
	protected $updateNulls = true;

	/**
	 * save
	 *
	 * @param Data|Entity $data
	 *
	 * @return  boolean
	 *
	 * @throws  \Windwalker\Record\Exception\NoResultException
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function save(Data $data)
	{
		$record = $this->getRecord();
		$key = $record->getKeyName();

		$isNew = true;
		$pk    = $data->$key;

		if ($pk)
		{
			$record->load($pk);
			$isNew = false;
		}

		$record->bind($data->dump(true));

		$this->prepareRecord($record);

		$record->validate()
			->store($this->updateNulls);

		if ($record->$key)
		{
			$this['item.pk'] = $data->$key = $record->$key;
		}

		$this['item.new'] = $isNew;
		$this['item.pkName'] = $key;

		$this->postSaveHook($record);

		$data->bind($record->dump());

		return true;
	}

	/**
	 * postSaveHook
	 *
	 * @param Record $record
	 *
	 * @return  void
	 */
	protected function postSaveHook(Record $record)
	{
	}

	/**
	 * prepareRecord
	 *
	 * @param Record $record
	 *
	 * @return  void
	 */
	protected function prepareRecord(Record $record)
	{
	}

	/**
	 * delete
	 *
	 * @param array $pk
	 *
	 * @return  boolean
	 *
	 * @throws \UnexpectedValueException
	 * @throws \Windwalker\Record\Exception\NoResultException
	 */
	public function delete($pk = null)
	{
		$pk = $pk ? : $this['item.pk'];

		$record = $this->getRecord();

		$record->load($pk)->delete();

		return true;
	}

	/**
	 * updateNulls
	 *
	 * @param   boolean $boolean
	 *
	 * @return  static|boolean
	 */
	public function updateNulls($boolean = null)
	{
		if ($boolean !== null)
		{
			$this->updateNulls = (bool) $boolean;

			return $this;
		}

		return $this->updateNulls;
	}
}
