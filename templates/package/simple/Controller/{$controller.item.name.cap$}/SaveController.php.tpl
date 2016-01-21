<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Model\{$controller.item.name.cap$}Model;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Data\Data;

/**
 * The SaveController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class SaveController extends AbstractSaveController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.item.name.lower$}';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = '{$controller.item.name.lower$}';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = '{$controller.list.name.lower$}';

	/**
	 * Property model.
	 *
	 * @var  {$controller.item.name.cap$}Model
	 */
	protected $model;

	/**
	 * Property useTransaction.
	 *
	 * @var  bool
	 */
	protected $useTransaction = false;

	/**
	 * postSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function postSave(Data $data)
	{
		parent::postSave($data);
	}
}
