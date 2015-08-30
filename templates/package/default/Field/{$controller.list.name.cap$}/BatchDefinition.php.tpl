<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.list.name.cap$};

use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\RadioField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;

/**
 * The {$controller.list.name.cap$}FilterDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class BatchDefinition implements FieldDefinitionInterface
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
		$form->wrap(null, 'batch', function (Form $form)
		{
			$form->add('language', new ListField)
				->label('Language')
				->set('class', '')
				->addOption(new Option('-- Select Language --', ''))
				->addOption(new Option('English', 'en-GB'))
				->addOption(new Option('Chinese Traditional', 'zh-TW'));

			$form->add('created_by', new TextField)
				->label('Author');
		});
	}
}
