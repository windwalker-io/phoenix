<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\DataMapper;

use Windwalker\Core\DataMapper\CoreDataMapper;

/**
 * The {$controller.item.name.cap$}Mapper class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Mapper extends CoreDataMapper
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected static $table = '{$controller.list.name.lower$}';

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
}
