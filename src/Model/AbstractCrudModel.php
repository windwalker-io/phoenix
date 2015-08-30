<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Data\Data;

/**
 * The AbstractCrudModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractCrudModel extends AbstractFormModel
{
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

		$record->bind($data->dump())
			->check()
			->store($record::UPDATE_NULLS);

		$key = $record->getKeyName();

		$this['item.pk'] = $data->$key = $record->$key;
		$this['pk_name'] = $key;

		return true;
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

		$record->delete($pk);

		return true;
	}
}
