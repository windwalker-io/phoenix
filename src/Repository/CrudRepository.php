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
use Windwalker\Ioc;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;

/**
 * The AbstractCrudModel class.
 *
 * @since  1.0
 */
class CrudRepository extends ItemRepository implements FormAwareRepositoryInterface, CrudRepositoryInterface
{
    use FormAwareRepositoryTrait;

    /**
     * Property updateNulls.
     *
     * @var  boolean
     */
    protected $updateNulls = true;

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
        // Prepare Record object, primary keys and dump input data
        $record = $this->getRecord();
        $keys   = array_filter((array) $this->getKeyName(true)); // Fix because Record return empty string
        $dumped = $data->dump(true);

        // Let's check if primary exists, do action for update.
        $conditions = array_intersect_key($dumped, array_flip($keys));

        if (array_filter($conditions)) {
            try {
                $record->load($conditions);
            } catch (NoResultException $e) {
                throw new NoResultException('Try to update a non-exists record to database.', $e->getCode(), $e);
            }
        }

        $record->bind($dumped);

        $this->triggerEvent('BeforeSave', [
            'conditions'  => $conditions,
            'data'        => $data,
            'record'      => $record,
            'updateNulls' => $this->updateNulls,
        ]);

        $this->prepareSave($record);

        $record->validate()
            ->store($this->updateNulls);

        $this->postSave($record);

        $this->triggerEvent('AfterSave', [
            'conditions'  => $conditions,
            'data'        => $data,
            'record'      => $record,
            'updateNulls' => $this->updateNulls,
        ]);

        $data->bind($record->dump(true));

        return $data;
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
     * @since  __DEPLOY_VERSION__
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
     * @since  __DEPLOY_VERSION__
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

        $record = $this->getRecord();

        $this->triggerEvent('BeforeDelete', [
            'conditions' => $conditions,
            'model' => $this,
            'record' => $record,
        ]);

        try {
            // Find record first to check we can delete it.
            $record->load($conditions);

            $this->prepareDelete($conditions, $record);

            $record->delete();

            $this->postDelete($conditions, $record);

            $this->triggerEvent('AfterDelete', [
                'conditions' => $conditions,
                'record' => $record,
                'result' => true,
            ]);
        } catch (NoResultException $e) {
            return false;
        }

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
     * @since  __DEPLOY_VERSION__
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
     * @since  __DEPLOY_VERSION__
     */
    protected function postDelete($conditions, Record $data)
    {
        //
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
        if ($boolean !== null) {
            $this->updateNulls = (bool) $boolean;

            return $this;
        }

        return $this->updateNulls;
    }
}
