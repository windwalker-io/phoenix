<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$};

use Phoenix\Field\ModalField;

/**
 * The {$controller.item.name.cap$}ModalField class.
 *
 * @since  {DEPLOY_VERSION}
 */
class {$controller.item.name.cap$}ModalField extends ModalField
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = '{$package.name.lower$}_{$controller.list.name.lower$}';

	/**
	 * Property view.
	 *
	 * @var  string
	 */
	protected $view = '{$controller.list.name.lower$}';

	/**
	 * Property package.
	 *
	 * @var  string
	 */
	protected $package = '{$package.name.lower$}';

	/**
	 * Property titleField.
	 *
	 * @var  string
	 */
	protected $titleField = 'title';

	/**
	 * Property keyField.
	 *
	 * @var  string
	 */
	protected $keyField = 'id';
}
