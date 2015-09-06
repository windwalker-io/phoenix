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
use Phoenix\Model\ListModel;
use Phoenix\Model\Filter\FilterHelperInterface;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Data\DataSet;
use Windwalker\Query\Query;

/**
 * The {$controller.list.name.cap$}Model class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$controller.list.name.cap$}Model extends ListModel
{
	/**
	 * Property allowFields.
	 *
	 * @var  array
	 */
	protected $allowFields = array();

	/**
	 * configureTables
	 *
	 * @return  void
	 */
	protected function configureTables()
	{
		$this->addTable('{$controller.item.name.lower$}', Table::{$controller.list.name.upper$});
	}

	/**
	 * configureFilters
	 *
	 * @param FilterHelperInterface $filterHelper
	 *
	 * @return  void
	 */
	protected function configureFilters(FilterHelperInterface $filterHelper)
	{
		//
	}
}
