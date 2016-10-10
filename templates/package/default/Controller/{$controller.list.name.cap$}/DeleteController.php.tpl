<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use Phoenix\Controller\Batch\AbstractDeleteController;
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;

/**
 * The DeleteController class.
 *
 * @since  1.0
 */
class DeleteController extends AbstractDeleteController
{
	use CsrfProtectionTrait;
	
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.list.name.cap$}';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = '{$controller.item.name.cap$}';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = '{$controller.list.name.cap$}';
}
