<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Model;

use Phoenix\Model\Filter\FilterHelperInterface;
use Phoenix\Model\ListModel;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Query\Query;

/**
 * The {$controller.list.name.cap$}Model class.
 * 
 * @since  1.0
 */
class {$controller.list.name.cap$}Model extends ModelRepository
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.list.name.lower$}';
}
