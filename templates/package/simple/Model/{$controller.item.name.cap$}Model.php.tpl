<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Model;

use {$package.namespace$}{$package.name.cap$}\Mapper\{$controller.item.name.cap$}Mapper;
use Phoenix\Model\ItemModel;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;

/**
 * The {$controller.item.name.cap$}Model class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Model extends ItemModel
{
	/**
	 * postGetItem
	 *
	 * @param Data $item
	 *
	 * @return  void
	 */
	protected function postGetItem(Data $item)
	{
	}
}
