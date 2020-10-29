<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Phoenix\Field;

use Phoenix\Script\CoreScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\Script\VueScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Form\Form;
use Windwalker\Ioc;
use Windwalker\String\Str;
use function Windwalker\arr;

/**
 * The RepeatableField class.
 *
 * @method  mixed|$this  layout(string $value = null)
 * @method  mixed|$this  configure(bool|callable|AbstractFieldDefinition $value = null)
 * @method  mixed|$this  sortable(bool $value = null)
 * @method  mixed|$this  ensureFirstRow(bool $value = null)
 * @method  mixed|$this  singleArray(bool $value = null)
 * @method  mixed|$this  placeholder(string $value = null)
 * @method  mixed|$this  max(int $value)
 *
 * @since  1.4.2
 */
class RepeatableField extends AbstractField
{
    public const LAYOUT_TABLE = 'phoenix.form.field.repeatable-table';
    public const LAYOUT_FLEX = 'phoenix.form.field.repeatable-flex';

    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = 'repeatable';

    /**
     * Property RepeatableForm.
     *
     * @var  Form
     */
    protected $innerForm;

    /**
     * prepareRenderInput
     *
     * @param array $attrs
     *
     * @return  void
     */
    public function prepare(&$attrs)
    {
        $attrs['type'] = 'hidden';
        $attrs['name'] = $this->getFieldName();
        $attrs['id'] = $this->getAttribute('id', $this->getId());
        $attrs['class'] = $this->getAttribute('class');
        $attrs['readonly'] = $this->getAttribute('readonly');
        $attrs['disabled'] = $this->getAttribute('disabled');
        $attrs['placeholder'] = $this->getAttribute('placeholder');
        $attrs['onchange'] = $this->getAttribute('onchange');
        $attrs['value'] = $this->getValue();
        $attrs['data-repeatable-store'] = true;

        $attrs['required'] = $this->required;
    }

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  mixed
     */
    public function buildInput($attrs)
    {
        $form = $this->getRepeatableForm();

        $this->prepareScript($attrs, $form);

        return WidgetHelper::render($this->get('layout', static::LAYOUT_TABLE), [
            'form' => $form,
            'attrs' => $attrs,
            'field' => $this,
        ], WidgetHelper::EDGE);
    }

    /**
     * getRepeatableForm
     *
     * @return  Form
     *
     * @since  1.8
     */
    protected function getRepeatableForm(): Form
    {
        $form = new Form($this->getFieldName(true));
        $singleArray = $this->singleArray();

        $configure = $this->configure();

        if (is_string($configure) && is_subclass_of($configure, AbstractFieldDefinition::class)) {
            $configure = Ioc::make($configure, ['form' => $form]);
        }

        if (is_callable($configure)) {
            $configure($form);
        } elseif ($configure instanceof AbstractFieldDefinition) {
            $form->defineFormFields($configure);
        } else {
            throw new \InvalidArgumentException('Wrong definition format.');
        }

        if ($singleArray) {
            if (\Windwalker\count($form->getFields()) > 2) {
                throw new \UnexpectedValueException(
                    'Repeatable field in singleArray mode should only contain key/value fields.'
                );
            }

            if (!$form->getField('value')) {
                throw new \UnexpectedValueException(
                    'Repeatable field in singleArray mode must have a `value` field.'
                );
            }
        }

        foreach ($form->getFields() as $field) {
            $field->set('id', null);
            $field->set('name', null);
            $field->attr(':id', sprintf("getId(i, item, '%s')", $field->getName()));
            $field->attr(':name', sprintf("getName(i, item, '%s')", $field->getName()));

            $field->attr(':disabled', 'attrs.disabled');
            $field->attr(':readonly', 'attrs.readonly');
            $field->disabled($this->get('disabled'));
            $field->readonly($this->get('readonly'));

            $field->setValue(null);

            $field->attr('v-model', 'item.' . $field->getName(true));
        }

        return $form;
    }

    /**
     * prepareScript
     *
     * @param array $attrs
     * @param Form  $form
     *
     * @return  void
     *
     * @since  1.8
     */
    protected function prepareScript(array $attrs, Form $form): void
    {
        static $inited = false;

        if (!$inited) {
            CoreScript::underscore();
            VueScript::core();
            VueScript::animate();
            VueScript::draggable();
            Asset::addJS(PhoenixScript::phoenixName() . '/js/field/repeatable.min.js');
        }

        $values = $this->getValue() ?: [];

        if (is_string($values)) {
            if (Str::startsWith($values, '[') || Str::startsWith($values, '{')) {
                $values = json_decode($values);
            } else {
                $values = array_filter(explode(',', $this->getValue()), 'strlen');
            }
        }

        $singleArray = $this->singleArray();
        $hasKey = (bool) $form->getField('key');

        $values = arr($values);

        if ($singleArray) {
            $values = $values->map(function ($v, $k) use ($hasKey) {
                if ($hasKey) {
                    return [
                        'key' => $k,
                        'value' => $v
                    ];
                }

                return [
                    'value' => $v
                ];
            }, true);
        }

        $values = $values->values()->dump();

        $fields = [];

        foreach ($form->getFields() as $field) {
            $fields[$field->getName()] = $field->getDefaultValue();
        }

        $control = $this->getFieldName();
        $id = $this->getId();
        $ensureFirstRow = (int) $this->ensureFirstRow();
        $values = Asset::getJSObject($values);
        $fields = Asset::getJSObject($fields);
        $attrsString = Asset::getJSObject($attrs);
        $max = Asset::getJSObject($this->max());

        $js = <<<JS
$(function () {
  var obj = $.extend(true, {}, window.RepeatableField, {
    el: '#{$attrs['id']}-wrap',
    data: {
      control: '$control',
      id: '$id',
      items: $values,
      fields: $fields,
      ensureFirstRow: $ensureFirstRow,
      attrs: $attrsString,
      max: $max
    }
  });
  
    var vm = new Vue(obj);
});
JS;

        PhoenixScript::domready($js);
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'layout',
            'configure',
            'sortable',
            'ensureFirstRow',
            'singleArray',
            'placeholder',
            'max'
        ]);
    }
}
