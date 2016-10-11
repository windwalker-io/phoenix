<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Record;

use Windwalker\Record\Record;

/**
 * The {$controller.item.name.cap$}Record class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Record extends Record
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = '{$controller.list.name.lower$}';

	/**
	 * Property keys.
	 *
	 * @var  string
	 */
	protected $keys = 'id';
}
