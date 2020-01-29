<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Repository;

use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Record\NestedRecord;
use Windwalker\Record\Record;
use function Windwalker\tap;
use Windwalker\Test\TestHelper;

/**
 * The NestedListModel class.
 *
 * @since  1.1
 */
class NestedAdminRepository extends AdminRepository
{
    /**
     * Property saveData.
     *
     * @var Data
     */
    protected $saveData;

    /**
     * prepareSave
     *
     * @param Record|NestedRecord $data
     *
     * @return  void
     *
     * @throws \Exception
     */
    protected function prepareSave(Record $data)
    {
        parent::prepareSave($data);

        // Auto set location for batch copy
        $key = $data->getKeyName();

        if (!$data->$key && !TestHelper::getValue($data, 'locationId')) {
            $data->setLocation($data->parent_id, NestedRecord::LOCATION_LAST_CHILD);
        }
    }

    /**
     * save
     *
     * @param DataInterface|Entity $data
     *
     * @return  DataInterface|Entity
     *
     * @throws \Exception
     */
    public function save(DataInterface $data)
    {
        $this->saveData = $data;

        try {
            return parent::save($data);
        } finally {
            $this->saveData = null;
        }
    }

    /**
     * postSaveHook
     *
     * @param Record $record
     *
     * @return  void
     * @throws \Exception
     */
    protected function postSave(Record $record)
    {
        /** @var NestedRecord $record */
        if ($this->saveData->parent_id !== null) {
            $record->rebuildPath();
        }
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
     * @throws \Exception
     */
    public function move($ids, $delta, $orderField = null)
    {
        if (!$ids || !$delta) {
            return true;
        }

        /** @var NestedRecord $record */
        $record     = $this->getRecord();
        $orderField = $orderField ?: $this->state->get('order.column', 'ordering');

        foreach ((array) $ids as $id) {
            $record->load($id);
            $record->move($delta, $orderField);
        }

        return true;
    }
}
