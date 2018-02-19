<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModelRepository;
use Windwalker\Data\DataInterface;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;

/**
 * The AbstractFormModel class.
 *
 * @since  1.0
 */
class ItemModel extends DatabaseModelRepository
{
    /**
     * getItem
     *
     * @param   mixed $conditions
     *
     * @return  Record
     * @throws \InvalidArgumentException
     */
    public function getItem($conditions = null)
    {
        $state = $this->state;

        $conditions = $conditions ?: $state['load.conditions'];

        return $this->fetch('item.' . json_encode($conditions), function () use ($conditions, $state) {
            if (!$conditions) {
                return $this->getRecord()->reset(false);
            }

            $item = $this->getRecord();

            try {
                $item->load($conditions);
            } catch (NoResultException $e) {
                return $item->reset(false);
            }

            $this->postGetItem($item);

            return $item;
        });
    }

    /**
     * postGetItem
     *
     * @param DataInterface $item
     *
     * @return  void
     */
    protected function postGetItem(DataInterface $item)
    {
    }
}
