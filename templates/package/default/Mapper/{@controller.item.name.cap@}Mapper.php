<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {@package.namespace@}{@package.name.cap@}\Mapper;

use {@package.namespace@}{@package.name.cap@}\Table\Table;
use Windwalker\DataMapper\DataMapper;

/**
 * The {@controller.item.name.cap@}Mapper class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {@controller.item.name.cap@}Mapper extends DataMapper
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = Table::{@controller.list.name.upper@};
}
