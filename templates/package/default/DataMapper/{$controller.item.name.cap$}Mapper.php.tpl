<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\DataMapper;

use {$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record;
use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Windwalker\DataMapper\AbstractDatabaseMapperProxy;
use Windwalker\Event\Event;

/**
 * The {$controller.item.name.cap$}Mapper class.
 *
 * @since  1.0
 */
class {$controller.item.name.cap$}Mapper extends AbstractDatabaseMapperProxy
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected static $table = Table::{$controller.list.name.upper$};
    /**
     * Property keys.
     *
     * @var  string
     */
    protected static $keys = 'id';
    /**
     * Property alias.
     *
     * @var  string
     */
    protected static $alias = '{$controller.item.name.lower$}';
    /**
     * Property dataClass.
     *
     * @var  string
     */
    protected static $dataClass = {$controller.item.name.cap$}Record::class;

    /**
     * onAfterFind
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterFind(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterCreate
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterCreate(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterUpdate
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterUpdate(Event $event)
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

    /**
     * onAfterFlush
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterFlush(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterUpdateAll
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterUpdateAll(Event $event)
    {
        // Add your logic
    }
}
