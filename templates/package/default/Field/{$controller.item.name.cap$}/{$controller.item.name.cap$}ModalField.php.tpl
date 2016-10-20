<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Phoenix\Field\ModalField;

/**
 * The {$controller.item.name.cap$}ModalField class.
 *
 * @since  1.0
 */
class {$controller.item.name.cap$}ModalField extends ModalField
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table = Table::{$controller.list.name.upper$};

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
