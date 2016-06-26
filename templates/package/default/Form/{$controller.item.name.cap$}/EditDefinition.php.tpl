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
use Windwalker\Form\Field;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
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
		$form->fieldset('basic', function(Form $form)
		{
			// ID
			$form->add('id', Field\HiddenField::class);

			// Title
			$form->add('title', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.title'))
				->setFilter('trim')
				->required(true);

			// Alias
			$form->add('alias', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.alias'));

			// Image
			$form->add('image', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.image'));

			// URL
			$form->add('url', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.url'))
				->setValidator(Rule\UrlValidator::class)
				->set('class', 'validate-url');

			// Example: {$controller.item.name.cap$} List
			$form->add('{$controller.item.name.lower$}_list', {$controller.item.name.cap$}ListField::class)
				->label('List Example');

			// Example: {$controller.item.name.cap$} Modal
			$form->add('{$controller.item.name.lower$}_modal', {$controller.item.name.cap$}ModalField::class)
				->label('Modal Example');
		});

		// Text Fieldset
		$form->fieldset('text', function(Form $form)
		{
			// Introtext
			$form->add('introtext', Field\TextareaField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.introtext'))
				->set('rows', 10);

			// Fulltext
			$form->add('fulltext', Field\TextareaField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.fulltext'))
				->set('rows', 10);
		});

		// Created fieldset
		$form->fieldset('created', function(Form $form)
		{
			// State
			$form->add('state', Field\RadioField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.state'))
				->set('class', 'btn-group')
				->set('default', 1)
				->option(Translator::translate('phoenix.grid.state.published'), '1')
				->option(Translator::translate('phoenix.grid.state.unpublished'), '0');

			// Created
			$form->add('created', Phoenix\Field\CalendarField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.created'));

			// Modified
			$form->add('modified', Phoenix\Field\CalendarField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modified'))
				->disabled();

			// Author
			$form->add('created_by', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.author'));

			// Modified User
			$form->add('modified_by', Field\TextField::class)
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modifiedby'))
				->disabled();
		});
	}
}
