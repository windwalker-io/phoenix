<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
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
	 * The default model.
	 *
	 * Keep model name here to make sure controller get singular model to handle update.
	 *
	 * @var  string
	 */
	protected $model = '{$controller.item.name.cap$}';
}
