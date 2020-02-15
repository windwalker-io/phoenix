<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Repository;

use Phoenix\Repository\Traits\FormAwareRepositoryTrait;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Event\Event;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;

/**
 * The AbstractCrudModel class.
 *
 * @since  1.0
 */
class CrudRepository extends ItemRepository implements
    FormAwareRepositoryInterface,
    CrudRepositoryInterface,
    GetOrCreateInterface
{
    use FormAwareRepositoryTrait;

    /**
     * Property updateNulls.
     *
     * @var  boolean
     */
    protected $updateNulls = true;

    /**
     * loadOrigin
     *
     * @param  DataInterface  $data
     *
     * @return  Record
     *
     * @throws \Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function loadOrigin(array $data, array &$conditions = null): Record
    {
        $conditions = $conditions ?? [];

        // Prepare Record object, primary keys and dump input data
        $record = $this->registerRecordEvents($this->getRecord());
        $keys   = array_filter((array) $this->getKeyName(true)); // Fix because Record return empty string

        // Let's check if primary exists, do action for update.
        $conditions = array_intersect_key($data, array_flip($keys));

        if (array_filter($conditions)) {
            try {
                $record->load($conditions);
            } catch (NoResultException $e) {
                throw new NoResultException('Try to update a non-exists record to database.', $e->getCode(), $e);
            }
        }

        return $record;
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
        $dumped = $data->dump(true);

        $record = $this->loadOrigin($dumped, $conditions);

        $record->bind($dumped);

        $this->triggerEvent('BeforeSave', [
            'conditions' => $conditions,
            'data' => $data,
            'record' => $record,
            'updateNulls' => $this->updateNulls,
        ]);

        $record->store($this->updateNulls);

        $this->triggerEvent('AfterSave', [
            'conditions' => $conditions,
            'data' => $data,
            'record' => $record,
            'updateNulls' => $this->updateNulls,
        ]);

        $data->bind($record->dump(true));

        return $data;
    }

    /**
     * copy
     *
     * @param array                  $conditions
     * @param array|object|callable  $newValue
     * @param bool                   $removeKey
     *
     * @return  Record
     *
     * @throws \Exception
     *
     * @since  1.8.13
     */
    public function copy($conditions = [], $newValue = null, bool $removeKey = true): Record
    {
        return $this->registerRecordEvents($this->getRecord())
            ->load($conditions)
            ->copy($newValue, $removeKey);
    }

    /**
     * getItemOrCreate
     *
     * @param mixed               $conditions
     * @param array|DataInterface $data
     * @param bool                $useConditions
     *
     * @return  Record
     *
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Exception
     * @since  1.8.4
     */
    public function getItemOrCreate($conditions, $data = [], bool $useConditions = true): Record
    {
        return $this->registerRecordEvents($this->getRecord())
            ->loadOrCreate($conditions, $data, $useConditions);
    }

    /**
     * updateOrCreate
     *
     * @param mixed         $data
     * @param array         $initData
     * @param array|mixed   $condFields
     *
     * @return  Record
     *
     * @throws \Exception
     *
     * @since  1.8.13
     */
    public function updateItemOrCreate(
        $data,
        array $initData = [],
        $condFields = null
    ): Record {
        $condFields = $condFields ?: $this->getKeyName(true);

        $conditions = [];

        foreach ($condFields as $field) {
            if (is_array($data)) {
                $conditions[$field] = $data[$field];
            } else {
                $conditions[$field] = $data->$field;
            }
        }

        return $this->registerRecordEvents($this->getRecord())
            ->loadOrCreate($conditions, $initData)
            ->bind($data)
            ->store($this->updateNulls);
    }

    /**
     * postSaveHook
     *
     * @param Record $record
     *
     * @return  void
     *
     * @deprecated  Use postSave() instead.
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
     *
     * @deprecated  Use prepareSave() instead.
     */
    protected function prepareRecord(Record $record)
    {
    }

    /**
     * prepareSave
     *
     * @param Record $data
     *
     * @return  void
     *
     * @since  1.6
     */
    protected function prepareSave(Record $data)
    {
        $this->prepareRecord($data);
    }

    /**
     * postSave
     *
     * @param Record $data
     *
     * @return  void
     *
     * @since  1.6
     */
    protected function postSave(Record $data)
    {
        $this->postSaveHook($data);
    }

    /**
     * delete
     *
     * @param array $conditions
     *
     * @return  boolean
     *
     * @throws \Exception
     */
    public function delete($conditions = null)
    {
        $conditions = $conditions ?: $this['load.conditions'];

        $record = $this->registerRecordEvents($this->getRecord());

        $this->triggerEvent('BeforeDelete', [
            'conditions' => $conditions,
            'model' => $this,
            'record' => $record,
        ]);

        do {
            $found = true;

            try {
                // Find record first to check we can delete it.
                $record->load($conditions);

                $record->delete();

                $this->triggerEvent('AfterDelete', [
                    'conditions' => $conditions,
                    'record' => $record,
                    'result' => true,
                ]);
            } catch (NoResultException $e) {
                $found = false;
            }
        } while ($found);

        return true;
    }

    /**
     * prepareDelete
     *
     * @param array  $conditions
     * @param Record $data
     *
     * @return  void
     *
     * @since  1.6
     */
    protected function prepareDelete($conditions, Record $data)
    {
        //
    }

    /**
     * postDelete
     *
     * @param array  $conditions
     * @param Record $data
     *
     * @return  void
     *
     * @since  1.6
     */
    protected function postDelete($conditions, Record $data)
    {
        //
    }

    /**
     * updateNulls
     *
     * @param boolean $boolean
     *
     * @return  static|boolean
     */
    public function updateNulls($boolean = null)
    {
        if ($boolean !== null) {
            $this->updateNulls = (bool) $boolean;

            return $this;
        }

        return $this->updateNulls;
    }

    /**
     * registerRecordEvents
     *
     * @param Record $record
     *
     * @return  Record
     *
     * @since  1.8.13
     */
    public function registerRecordEvents(Record $record): Record
    {
        parent::registerRecordEvents($record);

        $record->getDispatcher()->listen(
            'onBeforeStore',
            function () use ($record) {
                $this->prepareSave($record);

                $record->validate();
            }
        );

        $record->getDispatcher()->listen(
            'onAfterStore',
            function () use ($record) {
                $this->postSave($record);
            }
        );

        $record->getDispatcher()->listen(
            'onBeforeDelete',
            function (Event $event) use ($record) {
                $this->prepareDelete($event['conditions'], $record);
            }
        );

        $record->getDispatcher()->listen(
            'onAfterDelete',
            function (Event $event) use ($record) {
                $this->postDelete($event['conditions'], $record);
            }
        );

        return $record;
    }
}
