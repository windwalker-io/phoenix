<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\DataMapper;

use Windwalker\DataMapper\DataMapper;

/**
 * The {$controller.item.name.cap$}Mapper class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Mapper extends DataMapper
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = '{$controller.list.name.lower$}';
}
