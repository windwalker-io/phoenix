<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Core\User\User;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Data\DataInterface;
use Windwalker\Filter\OutputFilter;
use Windwalker\Record\Record;

/**
 * The AbstractAdminModel class.
 *
 * @since  1.0
 */
abstract class AdminModel extends CrudModel implements AdminRepositoryInterface
{
	/**
	 * Property reorderConditions.
	 *
	 * @var  array
	 */
	protected $reorderConditions = [];

	/**
	 * Property reorderPosition.
	 *
	 * @var  string
	 */
	protected $reorderPosition = self::ORDER_POSITION_LAST;

	/**
	 * save
	 *
	 * @param DataInterface $data
	 *
	 * @return  boolean
	 */
	public function save(DataInterface $data)
	{
		$result = parent::save($data);

		// Reorder
		if ($result && $this->get('order.position') == static::ORDER_POSITION_FIRST)
		{
			$pk = $data->{$this->getKeyName()};

			$this->reorder([$pk => 0]);

			$this->state->set('order.position', null);
		}

		return $result;
	}

	/**
	 * prepareRecord
	 *
	 * @param Record $record
	 *
	 * @return  void
	 *
	 * @throws \LogicException
	 */
	protected function prepareRecord(Record $record)
	{
		$date = $this->getDate();
		$user = $this->getUserData();
		$key  = $this->getKeyName();

		// Alias
		if ($record->hasField('alias'))
		{
			if (!$record->alias)
			{
				$record->alias = $this->handleAlias(trim($record->title));
			}
			else
			{
				$record->alias = $this->handleAlias(trim($record->alias));
			}

			if (!$record->alias)
			{
				$record->alias = OutputFilter::stringURLSafe(trim($date->toSql()));
			}
		}

		// Created date
		if ($record->hasField('created'))
		{
			if ($record->created)
			{
				$record->created = DateTime::toServerTime($record->created);
			}
			else
			{
				$record->created = $date->toSql();
			}
		}

		// Modified date
		if ($record->hasField('modified') && $record->$key)
		{
			$record->modified = $date->toSql();
		}

		// Created user
		if ($record->hasField('created_by') && !$record->created_by)
		{
			$record->created_by = $user->id;
		}

		// Modified user
		if ($record->hasField('modified_by') && $record->$key)
		{
			$record->modified_by = $user->id;
		}

		// Set Ordering or Nested ordering
		if ($record->hasField($this->state->get('order.column', 'ordering')))
		{
			if (empty($record->$key))
			{
				$this->setOrderPosition($record, $this->reorderPosition);
			}
		}
	}

	/**
	 * handleAlias
	 *
	 * @param   string  $alias
	 *
	 * @return  string
	 */
	public function handleAlias($alias)
	{
		return OutputFilter::stringURLSafe($alias);
	}

	/**
	 * getDate
	 *
	 * @param string $date
	 * @param bool   $tz
	 *
	 * @return DateTime
	 */
	public function getDate($date = 'now', $tz = DateTime::TZ_LOCALE)
	{
		return DateTime::create($date, $tz);
	}

	/**
	 * getUserData
	 *
	 * @param array $conditions
	 *
	 * @return  \Windwalker\Core\User\UserDataInterface
	 */
	public function getUserData($conditions = [])
	{
		return User::getUser($conditions);
	}

	/**
	 * reorder
	 *
	 * @param array  $orders
	 * @param string $orderField
	 *
	 * @return  boolean
	 */
	public function reorder($orders = [], $orderField = null)
	{
		if (!$orders)
		{
			return true;
		}

		$record     = $this->getRecord();
		$conditions = [];
		$orderField = $orderField ? : $this->state->get('order.column', 'ordering');

		// Update ordering values
		foreach ($orders as $pk => $orderNumber)
		{
			$record->load($pk);
			$record->$orderField = $orderNumber;

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
				$key = $this->getKeyName();
				$conditions[] = [
					'pk'   => $record->$key,
					'cond' => $condition
				];
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
	public function reorderAll($conditions = [], $orderField = null)
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
		$fields = (array) $this->state->get('order.condition_fields', $this->reorderConditions);

		$conditions = [];

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

		$position = $this->get('order.position', $position);

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
}
