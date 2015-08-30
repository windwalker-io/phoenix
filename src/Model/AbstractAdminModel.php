<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Record\Record;

/**
 * The AbstractAdminModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractAdminModel extends AbstractCrudModel
{
	const ORDER_POSITION_FIRST = 'first';
	const ORDER_POSITION_LAST  = 'last';

	/**
	 * Property reorderConditions.
	 *
	 * @var  array
	 */
	protected $reorderConditions = array();

	/**
	 * reorder
	 *
	 * @param array  $orders
	 * @param string $orderField
	 *
	 * @return  boolean
	 */
	public function reorder($orders = array(), $orderField = null)
	{
		if (!$orders)
		{
			return true;
		}

		$record     = $this->getRecord();
		$conditions = array();
		$orderField = $orderField ? : $this->state->get('order.column', 'ordering');

		// Update ordering values
		foreach ($orders as $pk => $order)
		{
			$record->load($pk);
			$record->$orderField = $order;

			$record->store();

			// Remember to reorder within position and client_id
			$condition = $this->getReorderConditions($record);
			$found     = false;

			// Found reorder condition if is cached.
			foreach ($conditions as $cond)
			{
				if ($cond['cond'] == $condition)
				{
					$found = true;
					break;
				}
			}

			// If not found, we add this condition to cache.
			if (!$found)
			{
				$key = $record->getKeyName();
				$conditions[] = array(
					'pk'   => $record->$key,
					'cond' => $condition
				);
			}
		}

		// Execute all reorder for each condition caches.
		foreach ($conditions as $cond)
		{
			$this->reorderAll($cond['cond'], $orderField);
		}

		return true;
	}

	/**
	 * reorder
	 *
	 * @param array  $conditions
	 * @param string $orderField
	 *
	 * @return bool
	 */
	public function reorderAll($conditions = array(), $orderField = null)
	{
		$orderField  = $orderField ? : $this->state->get('order.column', 'ordering');

		$mapper = $this->getDataMapper();

		$dataset = $mapper->find($conditions);

		$ordering = $dataset->$orderField;

		asort($ordering);

		$i = 1;

		foreach ($ordering as $k => $order)
		{
			$data = $dataset[$k];

			// Only update necessary item
			if ($data->$orderField != $i)
			{
				$data->$orderField = $i;

				$mapper->updateOne($data);
			}

			$i++;
		}

		$mapper->update($dataset);

		return true;
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
		$fields = (array) $this->state->get('order.condition.fields', $this->reorderConditions);

		$conditions = array();

		foreach ($fields as $field)
		{
			if ($record->hasField($field))
			{
				$conditions[] = $this->db->quoteName($field) . '=' . $this->db->quote($record->$field);
			}
		}

		return $conditions;
	}

	/**
	 * Method to set new item ordering as first or last.
	 *
	 * @param   Record $record    Item table to save.
	 * @param   string $position `first` or other are `last`.
	 *
	 * @return  void
	 */
	public function setOrderPosition(Record $record, $position = self::ORDER_POSITION_LAST)
	{
		$orderField = $this->state->get('order.column', 'ordering');

		if (!$record->hasField($orderField))
		{
			return;
		}

		if ($position == static::ORDER_POSITION_FIRST)
		{
			if (empty($record->$orderField))
			{
				$record->$orderField = 1;

				$this->state->set('order.position', static::ORDER_POSITION_FIRST);
			}
		}
		else
		{
			// Set ordering to the last item if not set
			if (empty($record->$orderField))
			{
				$query = $this->db->getQuery(true)
					->select(sprintf('MAX(%s)', $orderField))
					->from($record->getTableName());

				$condition = $this->getReorderConditions($record);

				// Condition should be an array.
				if (count($condition))
				{
					$query->where($this->getReorderConditions($record));
				}

				$max = $this->db->setQuery($query)->loadResult();

				$record->$orderField = $max + 1;
			}
		}
	}

	/**
	 * getMaxOrdering
	 *
	 * @param array $conditions
	 *
	 * @return  int
	 */
	public function getMaxOrdering($conditions = array())
	{
		$query = $this->db->getQuery(true)
			->select(sprintf('MAX(%s)', 'ordering'))
			->from($this->getDefaultTable());

		// Condition should be an array.
		if (count($conditions))
		{
			QueryHelper::buildWheres($query, $conditions);
		}

		return $this->db->setQuery($query)->loadResult();
	}
}
