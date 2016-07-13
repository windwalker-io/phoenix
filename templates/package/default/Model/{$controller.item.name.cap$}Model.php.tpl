<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Model;

use Phoenix\Model\AdminModel;
use Windwalker\Data\DataInterface;
use Windwalker\Record\Record;

/**
 * The {$controller.item.name.cap$}Model class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Model extends AdminModel
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.item.name.lower$}';

	/**
	 * Property record.
	 *
	 * @var  string
	 */
	protected $record = '{$controller.item.name.lower$}';

	/**
	 * Property mapper.
	 *
	 * @var  string
	 */
	protected $mapper = '{$controller.item.name.lower$}';

	/**
	 * Property reorderConditions.
	 *
	 * @var  array
	 */
	protected $reorderConditions = [];

	/**
	 * postGetItem
	 *
	 * @param DataInterface $item
	 *
	 * @return  void
	 */
	protected function postGetItem(DataInterface $item)
	{
		// Do some stuff
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
		parent::prepareRecord($record);
	}

	/**
	 * getReorderConditions
	 *
	 * @param Record $record
	 *
	 * @return  array  An array of conditions to add to ordering queries.
	 */
	public function getReorderConditions(Record $record)
	{
		return parent::getReorderConditions($record);
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   Record $record   Item table to save.
	 * @param   string $position `first` or other are `last`.
	 *
	 * @return  void
	 */
	public function setOrderPosition(Record $record, $position = self::ORDER_POSITION_LAST)
	{
		parent::setOrderPosition($record, $position);
	}
}
