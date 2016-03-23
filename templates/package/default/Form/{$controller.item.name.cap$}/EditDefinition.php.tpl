<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Form\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$}\{$controller.item.name.cap$}ListField;
use {$package.namespace$}{$package.name.cap$}\Field\{$controller.item.name.cap$}\{$controller.item.name.cap$}ModalField;
use Phoenix;
use Windwalker\Core\Language\Translator;
use Windwalker\Filter\InputFilter;
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Html\Option;
use Windwalker\Validator\Rule;

/**
 * The {$controller.item.name.cap$}EditDefinition class.
 *
 * @since  1.0
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
		// Basic fieldset
		$form->wrap('basic', null, function(Form $form)
		{
			// ID
			$form->add('id', new Field\HiddenField);

			// Title
			$form->add('title', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.title'))
				->setFilter('trim')
				->required(true);

			// Alias
			$form->add('alias', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.alias'));

			// Image
			$form->add('image', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.image'));

			// URL
			$form->add('url', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.url'))
				->setValidator(new Rule\UrlValidator)
				->set('class', 'validate-url');

			// Example: {$controller.item.name.cap$} List
			$form->add('{$controller.item.name.lower$}_list', new {$controller.item.name.cap$}ListField)
				->label('List Example');

			// Example: {$controller.item.name.cap$} Modal
			$form->add('{$controller.item.name.lower$}_modal', new {$controller.item.name.cap$}ModalField)
				->label('Modal Example');
		});

		// Text Fieldset
		$form->wrap('text', null, function(Form $form)
		{
			// Introtext
			$form->add('introtext', new Field\TextareaField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.introtext'))
				->set('rows', 10);

			// Fulltext
			$form->add('fulltext', new Field\TextareaField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.fulltext'))
				->set('rows', 10);
		});

		// Created fieldset
		$form->wrap('created', null, function(Form $form)
		{
			// State
			$form->add('state', new Field\RadioField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.state'))
				->set('class', 'btn-group')
				->set('default', 1)
				->addOption(new Option(Translator::translate('phoenix.grid.state.published'), '1'))
				->addOption(new Option(Translator::translate('phoenix.grid.state.unpublished'), '0'));

			// Created
			$form->add('created', new Phoenix\Field\CalendarField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.created'));

			// Modified
			$form->add('modified', new Phoenix\Field\CalendarField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modified'));

			// Author
			$form->add('created_by', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.author'));

			// Modified User
			$form->add('modified_by', new Field\TextField)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modifiedby'));
		});
	}
}
