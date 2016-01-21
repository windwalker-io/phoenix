<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use Phoenix\Controller\Batch\AbstractDeleteController;

/**
 * The DeleteController class.
 *
 * @since  1.0
 */
class DeleteController extends AbstractDeleteController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.list.name.lower$}';

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
