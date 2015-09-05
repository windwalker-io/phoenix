<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$};

use Phoenix\Field\ItemListField;

/**
 * The {$controller.item.name.cap$}Field class.
 *
 * @since  {DEPLOY_VERSION}
 */
class {$controller.item.name.cap$}ListField extends ItemListField
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = '{$controller.list.name.lower$}';

	/**
	 * Property ordering.
	 *
	 * @var  string
	 */
	protected $ordering = 'ordering';
}
