<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Repository;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\User\User;
use Windwalker\Filter\OutputFilter;
use Windwalker\Form\Filter\MaxLengthFilter;
use Windwalker\Record\Record;

/**
 * The AbstractAdminModel class.
 *
 * @since  1.0
 */
abstract class AdminRepository extends CrudRepository implements AdminRepositoryInterface
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
     * prepareSave
     *
     * @param Record $data
     *
     * @return  void
     *
     * @throws \Exception
     */
    protected function prepareSave(Record $data)
    {
        parent::prepareSave($data);

        $date = $this->getDate();
        $user = $this->getUserData();
        $key  = $this->getKeyName();

        // Alias
        if ($data->hasField('alias')) {
            if (!$data->alias) {
                $data->alias = $this->handleAlias(trim($data->title));
            } else {
                $data->alias = $this->handleAlias(trim($data->alias));
            }

            if (!$data->alias) {
                $data->alias = OutputFilter::stringURLSafe(trim($date->toSql()));
            }
        }

        // Created date
        if ($data->hasField('created')) {
            if (!$data->created || $data->created === $this->db->getNullDate()) {
                $data->created = $date->toSql();
            }
        }

        // Modified date
        if ($data->hasField('modified')) {
            $data->modified = $data->$key ? $date->toSql() : Chronos::getNullDate();
        }

        // Created user
        if ($data->hasField('created_by') && !$data->created_by) {
            $data->created_by = $user->id;
        }

        // Modified user
        if ($data->hasField('modified_by') && $data->$key) {
            $data->modified_by = $user->id;
        }

        // Set Ordering or Nested ordering
        if ($data->hasField($this->state->get('order.column', 'ordering'))) {
            if (empty($data->$key)) {
                $this->setOrderPosition($data, $this->reorderPosition);
            }
        }
    }

    /**
     * postSave
     *
     * @param Record $data
     *
     * @return  void
     *
     * @since  1.6
     * @throws \Exception
     */
    protected function postSave(Record $data)
    {
        parent::postSave($data);

        if ($this->get('order.position') === static::ORDER_POSITION_FIRST) {
            $pk = $data->{$this->getKeyName()};

            $this->reorder([$pk => 0]);
        }
    }

    /**
     * handleAlias
     *
     * @param   string $alias
     *
     * @return  string
     */
    public function handleAlias($alias)
    {
        return (new MaxLengthFilter(255))->clean(OutputFilter::stringURLSafe($alias));
    }

    /**
     * getDate
     *
     * @param string $date
     * @param bool   $tz
     *
     * @return Chronos
     */
    public function getDate($date = 'now', $tz = Chronos::TZ_LOCALE)
    {
        return Chronos::create($date, $tz);
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
     * move
     *
     * @param int|array $ids
     * @param int       $delta
     * @param string    $orderField
     *
     * @return  bool
     * @throws \Exception
     */
    public function move($ids, $delta, $orderField = null)
    {
        if (!$ids || !$delta) {
            return true;
        }

        $record     = $this->getRecord();
        $key        = $record->getKeyName();
        $orderField = $orderField ?: $this->state->get('order.column', 'ordering');

        foreach ((array) $ids as $id) {
            $record->load($id);

            $condition = $this->getReorderConditions($record);

            if ($delta > 0) {
                // Move down
                $condition[] = $this->db->quoteName($orderField) . ' > ' . (int) $record->$orderField;
                $dir = 'ASC';
            } else {
                // Move up
                $condition[] = $this->db->quoteName($orderField) . ' < ' . (int) $record->$orderField;
                $dir = 'DESC';
            }

            $neighbor = $record->getDataMapper()->findOne($condition, $orderField . ' ' . $dir);

            if ($neighbor->isNull()) {
                continue;
            }

            $ordering = [
                $record->$key => (int) $neighbor->$orderField,
                $neighbor->$key => (int) $record->$orderField,
            ];

            $this->reorder($ordering, $orderField);
        }

        return true;
    }

    /**
     * reorder
     *
     * @param array  $orders
     * @param string $orderField
     *
     * @return  boolean
     * @throws \Exception
     */
    public function reorder($orders = [], $orderField = null)
    {
        if (!$orders) {
            return true;
        }

        $record     = $this->getRecord();
        $conditions = [];
        $orderField = $orderField ?: $this->state->get('order.column', 'ordering');

        // Update ordering values
        foreach ($orders as $pk => $orderNumber) {
            $record->load($pk);
            $record->$orderField = $orderNumber;

            $record->store();

            // Remember to reorder within position and client_id
            $condition = $this->getReorderConditions($record);
            $found     = false;

            // Found reorder condition if is cached.
            foreach ($conditions as $cond) {
                if ($cond['cond'] == $condition) {
                    $found = true;
                    break;
                }
            }

            // If not found, we add this condition to cache.
            if (!$found) {
                $key          = $this->getKeyName();
                $conditions[] = [
                    'pk' => $record->$key,
                    'cond' => $condition,
                ];
            }
        }

        // Execute all reorder for each condition caches.
        foreach ($conditions as $cond) {
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
     * @throws \Exception
     */
    public function reorderAll($conditions = [], $orderField = null)
    {
        $orderField = $orderField ?: $this->state->get('order.column', 'ordering');

        $mapper = $this->getDataMapper();

        $dataset = $mapper->find($conditions);

        $ordering = $dataset->$orderField;

        asort($ordering);

        $i = 1;

        foreach ($ordering as $k => $order) {
            $data = $dataset[$k];

            // Only update necessary item
            if ($data->$orderField != $i) {
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

        foreach ($fields as $field) {
            if ($record->hasField($field)) {
                $conditions[] = $this->db->quoteName($field) . '=' . $this->db->quote($record->$field);
            }
        }

        return $conditions;
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
        $orderField = $this->state->get('order.column', 'ordering');

        if (!$record->hasField($orderField)) {
            return;
        }

        $position = $this->get('order.position', $position);

        if ($position === static::ORDER_POSITION_FIRST) {
            if (empty($record->$orderField)) {
                $record->$orderField = 1;

                $this->state->set('order.position', static::ORDER_POSITION_FIRST);
            }
        } else {
            // Set ordering to the last item if not set
            if (empty($record->$orderField)) {
                $query = $this->db->getQuery(true)
                    ->select(sprintf('MAX(%s)', $orderField))
                    ->from($this->db->quoteName($record->getTableName()));

                $condition = $this->getReorderConditions($record);

                // Condition should be an array.
                if (count($condition)) {
                    $query->where($this->getReorderConditions($record));
                }

                $max = $this->db->setQuery($query)->loadResult();

                $record->$orderField = $max + 1;
            }
        }
    }
}
