<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Form;

/**
 * The InlineField class.
 *
 * @method  mixed|$this  asGroup(bool $value = null)
 * @method  mixed|$this  layout(string $value = null)
 *
 * @since  1.4.2
 */
class InlineField extends TextField
{
    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = 'inline';

    /**
     * Property inlineForm.
     *
     * @var  Form
     */
    protected $inlineForm;

    /**
     * prepareRenderInput
     *
     * @param array $attrs
     *
     * @return  array
     */
    public function prepare(&$attrs)
    {
        return $attrs;
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
        $this->form = $this->form ?: new Form;

        return WidgetHelper::render($this->get('layout', 'phoenix.form.field.inline'), [
            'form' => $this->form,
            'attrs' => $attrs,
            'field' => $this,
        ], WidgetHelper::EDGE);
    }

    /**
     * configure
     *
     * @param callable $handler
     *
     * @return  void
     */
    public function configure(callable $handler)
    {
        $this->form = $this->form ?: new Form;

        $group = $this->get('as_group') ? $this->getGroup() . '.' . $this->getName() : $this->getGroup();

        $this->form->wrap('inline-' . $this->getName(true), $group, $handler);
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'asGroup' => 'as_group',
            'layout',
        ]);
    }
}
