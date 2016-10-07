<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Model;

use Phoenix\Model\ItemModel;
use Windwalker\Data\DataInterface;

/**
 * The {$controller.item.name.cap$}Model class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Model extends ItemModel
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.item.name.cap$}';

	/**
	 * postGetItem
	 *
	 * @param DataInterface $item
	 *
	 * @return  void
	 */
	protected function postGetItem(DataInterface $item)
	{
	}
}
