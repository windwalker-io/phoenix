<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Repository;

use Phoenix\Repository\ItemRepository;
use Windwalker\Data\DataInterface;

/**
 * The {$controller.item.name.cap$}Repository class.
 *
 * @since  1.0
 */
class {$controller.item.name.cap$}Repository extends ItemRepository
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = '{$controller.item.name.cap$}';

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
