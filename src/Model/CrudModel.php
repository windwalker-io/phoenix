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
	 * @return  boolean
	 *
	 * @throws  \Windwalker\Record\Exception\NoResultException
	 * @throws  \InvalidArgumentException
	 * @throws  \RuntimeException
	 */
	public function save(DataInterface $data)
	{
		$record = $this->getRecord();
		$key = $record->getKeyName();

		$pk = $data->$key;

		if ($pk)
		{
			$record->load($pk);
		}

		$record->bind($data->dump(true));

		$this->prepareRecord($record);

		$record->validate()
			->store($this->updateNulls);

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

		$record->load($conditions)->delete();

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
