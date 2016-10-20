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
use Phoenix\Form\PhoenixFieldTrait;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Field;
use Windwalker\Form\Form;
use Windwalker\Validator\Rule;

/**
 * The {$controller.item.name.cap$}EditDefinition class.
 *
 * @since  1.0
 */
class EditDefinition extends AbstractFieldDefinition
{
	use PhoenixFieldTrait;

	/**
	 * Define the form fields.
	 *
	 * @param Form $form The Windwalker form object.
	 *
	 * @return  void
	 */
	public function doDefine(Form $form)
	{
		// Basic fieldset
		$this->fieldset('basic', function(Form $form)
		{
			// ID
			$this->hidden('id');

			// Title
			$this->text('title')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.title'))
				->setFilter('trim')
				->required(true);

			// Alias
			$this->text('alias')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.alias'));

			// Image
			$this->text('image')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.image'));

			// URL
			$this->text('url')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.url'))
				->setValidator(Rule\UrlValidator::class)
				->set('class', 'validate-url');

			// Example: {$controller.item.name.cap$} List
			$this->add('{$controller.item.name.lower$}_list', {$controller.item.name.cap$}ListField::class)
				->label('List Example');

			// Example: {$controller.item.name.cap$} Modal
			$this->add('{$controller.item.name.lower$}_modal', {$controller.item.name.cap$}ModalField::class)
				->label('Modal Example');
		});

		// Text Fieldset
		$this->fieldset('text', function(Form $form)
		{
			// Introtext
			$this->textarea('introtext')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.introtext'))
				->set('rows', 10);

			// Fulltext
			$this->textarea('fulltext')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.fulltext'))
				->set('rows', 10);
		});

		// Created fieldset
		$this->fieldset('created', function(Form $form)
		{
			// State
			$this->radio('state')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.state'))
				->set('class', 'btn-group')
				->set('default', 1)
				->option(Translator::translate('phoenix.grid.state.published'), '1')
				->option(Translator::translate('phoenix.grid.state.unpublished'), '0');

			// Created
			$this->calendar('created')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.created'));

			// Modified
			$this->calendar('modified')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modified'))
				->disabled();

			// Author
			$this->text('created_by')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.author'));

			// Modified User
			$this->text('modified_by')
				->label(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.modifiedby'))
				->disabled();
		});
	}
}
