<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Model;

use {$package.namespace$}{$package.name.cap$}\Mapper\{$controller.item.name.cap$}Mapper;
use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Phoenix\Model\AbstractListModel;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Data\DataSet;

/**
 * The {$controller.list.name.cap$}Model class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$controller.list.name.cap$}Model extends AbstractListModel
{
	/**
	 * configureTables
	 *
	 * @return  void
	 */
	protected function configureTables()
	{
		$this->addTable('{$controller.item.name.lower$}', Table::{$controller.list.name.upper$});
	}
}
