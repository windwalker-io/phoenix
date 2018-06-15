<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Form;

use Phoenix\Script\BootstrapScript;
use Phoenix\Script\JQueryScript;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Form\Field\CheckboxesField;
use Windwalker\Form\Field\CheckboxField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\String\StringHelper;
use Windwalker\Utilities\Arr;

/**
 * The FieldHelper class.
 *
 * @since  1.3
 */
class FieldHelper
{
    /**
     * handle
     *
     * @param AbstractField $field
     * @param array         $attribs
     *
     * @return  void
     */
    public static function handle(AbstractField $field, array &$attribs)
    {
        static::showon($field, $attribs, $field->get('showon'));

        // Hidden
        if ($field instanceof HiddenField) {
            $attribs['style'] = isset($attribs['style']) ? $attribs['style'] : '';
            $attribs['style'] .= 'display: none;';
        }

        if (BootstrapScript::$currentVersion === 4) {
            Arr::def($attribs, 'class', '');
            $attribs['class'] .= ' row';
        }
    }

    /**
     * showon
     *
     * @param AbstractField $field
     * @param array         $attribs
     * @param array         $showon
     *
     * @return  void
     */
    public static function showon(AbstractField $field, array $attribs, $showon = null)
    {
        if ($showon && is_array($showon)) {
            $form = $field->getForm();

            foreach ($showon as $selector => $values) {
                $values = array_map('strval', (array) $values);
                list($group, $name) = StringHelper::explode('.', $selector, 2, 'array_unshift');
                $target = $form->getField($name, $group);

                $conditions = [];

                if ($target instanceof CheckboxesField) {
                    foreach ($values as $value) {
                        $conditions[sprintf("input[name='%s[]'][value='%s']", $target->getFieldName(), $value)] = ['checked' => true];
                    }
                } elseif ($target instanceof CheckboxField) {
                    foreach ($values as $value) {
                        $conditions[sprintf("input[name='%s'][value='%s']", $target->getFieldName(), $value)] = ['checked' => true];
                    }
                } else {
                    $conditions = [
                        sprintf('input[name="%s"]', $target->getFieldName()) => ['values' => $values],
                    ];
                }

                JQueryScript::dependsOn('#' . Arr::get($attribs, 'id'), $conditions);
            }
        }
    }
}
