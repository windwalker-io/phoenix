<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Record\NestedRecord;
use Windwalker\Record\Record;
use Windwalker\Test\TestHelper;

/**
 * The NestedListModel class.
 *
 * @since  1.1
 */
class NestedAdminModel extends AdminModel
{
	/**
	 * prepareRecord
	 *
	 * @param Record $record
	 *
	 * @return  void
	 */
	protected function prepareRecord(Record $record)
	{
		/** @var NestedRecord $record */
		parent::prepareRecord($record);

		// Auto set location for batch copy
		$key = $record->getKeyName();

		if (!$record->$key && !TestHelper::getValue($record, 'locationId'))
		{
			$record->setLocation($record->parent_id, $record::LOCATION_LAST_CHILD);
		}
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
		/** @var NestedRecord $record */
		$record->rebuild();
	}

	/**
	 * reorder
	 *
	 * @param array  $conditions
	 * @param string $orderField
	 *
	 * @return bool
	 */
	public function reorderAll($conditions = [], $orderField = null)
	{
		/** @var NestedRecord $record */
		$record = $this->getRecord();

		$record->rebuild();

		return true;
	}

	/**
	 * move
	 *
	 * @param int|array $ids
	 * @param int       $delta
	 * @param string    $orderField
	 *
	 * @return  bool
	 */
	public function move($ids, $delta, $orderField = null)
	{
		if (!$ids || !$delta)
		{
			return true;
		}

		/** @var NestedRecord $record */
		$record     = $this->getRecord();
		$orderField = $orderField ? : $this->state->get('order.column', 'ordering');

		foreach ((array) $ids as $id)
		{
			$record->load($id);
			$record->move($delta, $orderField);
		}

		return true;
	}
}
