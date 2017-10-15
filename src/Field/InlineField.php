<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Phoenix\Form\InlineDefinition;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\TextField;
use Windwalker\Form\Form;

/**
 * The InlineField class.
 *
 * @method  mixed|$this  asGroup(bool $value = null)
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
		$this->inlineForm->setRenderer($this->form->getRenderer());

		foreach ($this->inlineForm as $field)
		{
			foreach ($this->getAttributes() as $name => $value)
			{
				$field->setAttribute($name, $value);
			}
		}

		return WidgetHelper::render('phoenix.form.field.inline', [
			'form'  => $this->inlineForm,
			'attrs' => $attrs,
			'field' => $this
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
		if (!$this->inlineForm)
		{
			$this->inlineForm = new Form($this->get('control', $this->getControl()));
		}

		$definition = new InlineDefinition;
		$this->inlineForm->defineFormFields($definition);

		if ($this->get('as_group'))
		{
			$definition->group($this->getName(), function () use ($handler, $definition)
			{
				$handler($definition);
			});
		}
		else
		{
			$handler($definition);
		}
	}

	/**
	 * getAccessors
	 *
	 * @return  array
	 */
	protected function getAccessors()
	{
		return array_merge(parent::getAccessors(), [
			'asGroup' => 'as_group'
		]);
	}
}
