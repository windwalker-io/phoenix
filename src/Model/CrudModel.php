<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Phoenix\Model\Traits\FormAwareRepositoryTrait;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;

/**
 * The AbstractCrudModel class.
 *
 * @since  1.0
 */
class CrudModel extends ItemModel implements FormAwareRepositoryInterface, CrudRepositoryInterface
{
	use FormAwareRepositoryTrait;
	
	/**
	 * Property updateNulls.
	 *
	 * @var  boolean
	 */
	protected $updateNulls = true;

	/**
	 * save
	 *
	 * @param DataInterface|Entity $data
	 *
	 * @return  DataInterface|Entity
	 *
	 * @throws  \Windwalker\Record\Exception\NoResultException
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function save(DataInterface $data)
	{
		// Prepare Record object, primary keys and dump input data
		$record = $this->getRecord();
		$keys   = $record->getKeyName(true) ? : []; // Fix because Record return empty string
		$dumped = $data->dump(true);

		// Let's check if primary exists, do action for update.
		$conditions = array_intersect_key($dumped, array_flip($keys));

		if (array_filter($conditions))
		{
			$record->load($conditions);
		}

		$record->bind($dumped);

		$this->prepareRecord($record);

		$record->validate()
			->store($this->updateNulls);

		$this->postSaveHook($record);

		$data->bind($record->dump(true));

		return $data;
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
	 * @param array $conditions
	 *
	 * @return  boolean
	 *
	 * @throws \UnexpectedValueException
	 * @throws \Windwalker\Record\Exception\NoResultException
	 */
	public function delete($conditions = null)
	{
		$conditions = $conditions ? : $this['load.conditions'];

		$record = $this->getRecord();

		try
		{
			// Find record first to check we can delete it.
			$record->load($conditions)->delete();
		}
		catch (NoResultException $e)
		{
			return false;
		}

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
