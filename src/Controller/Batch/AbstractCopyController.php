<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Data\DataInterface;
use Windwalker\String\StringHelper;

/**
 * The CopyController class.
 *
 * @since  1.0.5
 */
abstract class AbstractCopyController extends AbstractBatchController
{
    /**
     * Property action.
     *
     * @var  string
     */
    protected $action = 'copy';

    /**
     * Property allowNullData.
     *
     * @var  boolean
     */
    protected $allowNullData = true;

    /**
     * Which fields should increment.
     *
     * @var array
     */
    protected $incrementFields = [
        'title' => StringHelper::INCREMENT_STYLE_DEFAULT,
        'alias' => StringHelper::INCREMENT_STYLE_DASH,
    ];

    /**
     * Property dateFields.
     *
     * @var  array
     */
    protected $createdFields = [
        'created',
    ];

    /**
     * A hook before main process executing.
     *
     * @return  void
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();

        $this->data = array_merge($this->input->getVar('batch', []), (array) $this->data);
    }

    /**
     * save
     *
     * @param int|string    $pk
     * @param DataInterface $data
     *
     * @return DataInterface|\Windwalker\DataMapper\Entity\Entity
     *
     * @throws \Exception
     */
    protected function save($pk, DataInterface $data)
    {
        // We load existing item first and bind data into it.
        $record = $this->repository->getRecord();

        $record->reset();

        $record->load($pk);

        $record->bind($data);

        $recordClone = $this->repository->getRecord();

        $condition = [];

        // Check table has increment fields, default is title and alias.
        foreach ($this->incrementFields as $field => $type) {
            if ($record->hasField($field)) {
                $condition[$field] = $record[$field];
            }
        }

        // Recheck item with same conditions(default is title & alias), if true, increment them.
        // If no item got, means it is the max number.
        do {
            $result = true;

            try {
                $recordClone->load($condition);

                foreach ($this->incrementFields as $field => $type) {
                    if ($record->hasField($field)) {
                        $record[$field] = $condition[$field] = StringHelper::increment($record[$field], $type);
                    }
                }
            } catch (\RuntimeException $e) {
                $result = false;
            }

            $recordClone->reset(false);
        } while ($result);

        unset($record->{$this->keyName});

        // Update created date
        foreach ($this->createdFields as $field) {
            if ($record->hasField($field)) {
                $record->$field = $this->getDate();
            }
        }

        return $this->repository->save($record);
    }

    /**
     * getDate
     *
     * @return  string
     *
     * @since  1.6
     */
    protected function getDate()
    {
        return Chronos::create()->toSql();
    }
}
