<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$}\Batch;

use Phoenix\Controller\Batch\AbstractTrashController;
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;

/**
 * The TrashController class.
 *
 * @since  1.0
 */
class TrashController extends AbstractTrashController
{
	use CsrfProtectionTrait;
}
