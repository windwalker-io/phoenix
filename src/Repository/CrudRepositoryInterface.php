<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Repository;

use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Record\Record;

/**
 * The CrudModelInterface class.
 *
 * @since  1.1
 */
interface CrudRepositoryInterface
{
    /**
     * getItem
     *
     * @param   mixed $pk
     *
     * @return  DataInterface|Entity
     */
    public function getItem($pk = null);

    /**
     * save
     *
     * @param DataInterface|Entity $data
     *
     * @return  boolean
     *
     * @throws  \RuntimeException
     */
    public function save(DataInterface $data);

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
     * @since  __DEPLOY_VERSION__
     */
    public function copy($conditions = [], $newValue = null, bool $removeKey = true): Record;

    /**
     * delete
     *
     * @param array $conditions
     *
     * @return  boolean
     *
     * @throws \UnexpectedValueException
     */
    public function delete($conditions = null);
}
