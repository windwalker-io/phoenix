<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseRepositoryInterface;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;

/**
 * The CrudModelInterface class.
 *
 * @since  1.1
 */
interface CrudRepositoryInterface extends DatabaseRepositoryInterface
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
