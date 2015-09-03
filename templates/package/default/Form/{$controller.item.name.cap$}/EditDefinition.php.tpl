<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Form\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$}\{$controller.item.name.cap$}ListField;
use {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$}\{$controller.item.name.cap$}ModalField;
use Phoenix\Field\CalendarField;
use Phoenix\Field\ModalField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Field\RadioField;
use Windwalker\Form\Field\TextareaField;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;
use Windwalker\Validator\Rule\UrlValidator;

/**
 * The {$controller.item.name.cap$}EditDefinition class.
 *
 * @since  {DEPLOY_VERSION}
 */
class EditDefinition implements FieldDefinitionInterface
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
		$form->wrap('basic', null, function(Form $form)
		{
			$form->add('id', new HiddenField);

			$form->add('title', new TextField)
				->label('Title')
				->required(true);

			$form->add('alias', new TextField)
				->label('Alias');

			$form->add('images', new TextField)
				->label('Images');

			$form->add('url', new TextField)
				->label('URL')
				->set('class', 'validate-url')
				->setValidator(new UrlValidator);

			$form->add('version', new {$controller.item.name.cap$}ModalField)
				->label('{$controller.item.name.cap$}');
		});

		$form->wrap('text', null, function(Form $form)
		{
			$form->add('introtext', new TextareaField)
				->label('Intro Text')
				->set('rows', 10);

			$form->add('fulltext', new TextareaField)
				->label('Full Text')
				->set('rows', 10);
		});

		$form->wrap('created', null, function(Form $form)
		{
			$form->add('state', new RadioField)
				->label('State')
				->required(true)
				->set('class', 'btn-group')
				->addOption(new Option('Published', '1'))
				->addOption(new Option('Unpublished', '0'));

			$form->add('version2', new TextField)
				->label('Version');

			$form->add('created', new CalendarField)
				->label('Created Time');

			$form->add('modified', new CalendarField)
				->label('Modified Time');

			$form->add('created_by', new TextField)
				->label('Author');

			$form->add('modified_by', new TextField)
				->label('Modified User');
		});
	}
}
