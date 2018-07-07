<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Record;

use {$package.namespace$}{$package.name.cap$}\Record\Columns\{$controller.item.name.cap$}DataInterface;
use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Windwalker\Event\Event;
use Windwalker\Record\Record;

/**
 * The {$controller.item.name.cap$}Record class.
 *
 * @since  1.0
 */
class {$controller.item.name.cap$}Record extends Record implements {$controller.item.name.cap$}DataInterface
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected $table = Table::{$controller.list.name.upper$};

    /**
     * Property keys.
     *
     * @var  string
     */
    protected $keys = 'id';

    /**
     * onAfterLoad
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterLoad(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterStore
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterStore(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterDelete
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterDelete(Event $event)
    {
        // Add your logic
    }
}
