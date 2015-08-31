<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$}\Field\{$controller.list.name.cap$};

use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;

/**
 * The {$controller.list.name.cap$}FilterDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class FilterDefinition implements FieldDefinitionInterface
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
		$form->wrap(null, 'search', function (Form $form)
		{
			$form->add('field', new ListField)
				->set('class', '')
				->addOption(new Option('All', '*'))
				->addOption(new Option('Title', '{$controller.item.name.lower$}.title'))
				->addOption(new Option('Alias', '{$controller.item.name.lower$}.alias'));

			$form->add('content', new TextField)
				->set('placeholder', 'Search');
		});

		$form->wrap(null, 'filter', function(Form $form)
		{
			$form->add('{$controller.item.name.lower$}.state', new ListField)
				->label('state')
				// Add empty option to support single deselect button
				->addOption(new Option('', ''))
				->addOption(new Option('-- Select State --', ''))
				->addOption(new Option('Published', '1'))
				->addOption(new Option('Unpublished', '0'))
				->set('onchange', 'this.form.submit()');
		});
	}
}
