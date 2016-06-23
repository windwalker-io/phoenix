<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Phoenix\Model\Traits\FormModelTrait;
use Windwalker\Data\Data;
use Windwalker\Record\Record;

/**
 * The AbstractCrudModel class.
 *
 * @since  1.0
 */
class CrudModel extends ItemModel implements FormModelInterface
{
	use FormModelTrait;

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
	 * @param Data $data
	 *
	 * @return  boolean
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

		$record->bind($data->dump());

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
	 */
	public function delete($pk = array())
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
