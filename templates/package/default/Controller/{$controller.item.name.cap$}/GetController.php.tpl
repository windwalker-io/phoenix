<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Model\{$controller.item.name.cap$}Model;
use {$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$}\{$controller.item.name.cap$}HtmlView;
use Phoenix\Controller\Display\ItemGetController;
use Windwalker\Core\Controller\Controller;

/**
 * The GetController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class GetController extends ItemGetController
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
}
