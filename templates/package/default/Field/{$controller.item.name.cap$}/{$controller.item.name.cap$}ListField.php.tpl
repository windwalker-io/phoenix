<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Phoenix\Field\ItemListField;

/**
 * The {$controller.item.name.cap$}Field class.
 *
 * @since  1.0
 */
class {$controller.item.name.cap$}ListField extends ItemListField
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected $table = Table::{$controller.list.name.upper$};

    /**
     * Property ordering.
     *
     * @var  string
     */
    protected $ordering = null;
}
