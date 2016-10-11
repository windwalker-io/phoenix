<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\DataMapper;

use {$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record;
use Windwalker\DataMapper\AbstractDatabaseMapperProxy;

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

	/**
	 * Property dataClass.
	 *
	 * @var  string
	 */
	protected static $dataClass = {$controller.item.name.cap$}Record::class;
}
