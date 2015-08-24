<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Form;

use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The NullFiledDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class NullFiledDefinition implements FieldDefinitionInterface
{
	/**
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 */
	public function define(Form $form)
	{

	}
}
