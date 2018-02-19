<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Form\{$controller.list.name.cap$};

use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

/**
 * The GridDefinition class.
 *
 * @since  1.1
 */
class GridDefinition extends AbstractFieldDefinition
{
    /**
     * Define the form fields.
     *
     * @param Form $form The Windwalker form object.
     *
     * @return  void
     */
    protected function doDefine(Form $form)
    {
        /*
		 * Search Control
		 * -------------------------------------------------
		 * Add search fields as options, by default, model will search all columns.
		 * If you hop that user can choose a field to search, change "display" to true.
		 */
        $this->group(
            'search', function (Form $form) {
            // Search Field
            $this->list('field')
                ->label(Translator::translate('phoenix.grid.search.field.label'))
                ->set('display', false)
                ->defaultValue('*')
                ->option(Translator::translate('phoenix.core.all'), '*')
                ->option(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.title'), '{$controller.item.name.lower$}.title')
                ->option(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.field.alias'), '{$controller.item.name.lower$}.alias');

            // Search Content
            $this->text('content')
                ->label(Translator::translate('phoenix.grid.search.label'))
                ->placeholder(Translator::translate('phoenix.grid.search.label'));
        }
        );

        /*
		 * Filter Control
		 * -------------------------------------------------
		 * Add filter fields to this section.
		 * Remember to add onchange event => this.form.submit(); or Phoenix.post();
		 *
		 * You can override filter actions in {$controller.list.name.cap$}Model::configureFilters()
		 */
        $this->group(
            'filter', function (Form $form) {
            // State
            $this->list('{$controller.item.name.lower$}.state')
                ->label('State')
                ->addClass('hasChosen')
                // Add empty option to support single deselect button
                ->option('', '')
                ->option(Translator::translate('{$package.name.lower$}.{$controller.item.name.lower$}.filter.state.select'), '')
                ->option(Translator::translate('phoenix.grid.state.published'), '1')
                ->option(Translator::translate('phoenix.grid.state.unpublished'), '0')
                ->onchange('this.form.submit()');
        }
        );

        /*
		 * This is batch form definition.
		 * -----------------------------------------------
		 * Every field is a table column.
		 * For example, you can add a 'category_id' field to update item category.
		 */
        $this->group(
            'batch', function (Form $form) {
            // Language
            $this->list('language')
                ->label('Language')
                ->addClass('col-md-12 hasChosen')
                ->option('-- Select Language --', '')
                ->option('English', 'en-GB')
                ->option('Chinese Traditional', 'zh-TW');

            // Author
            $this->text('created_by')
                ->label('Author');
        }
        );
    }
}
