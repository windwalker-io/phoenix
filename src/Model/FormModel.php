<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Phoenix\Form\NullFiledDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Form\Validate\ValidateResult;
use Windwalker\Ioc;

/**
 * The AbstractFormModel class.
 * 
 * @since  1.0
 */
class FormModel extends ItemModel
{
	/**
	 * Property formRenderer.
	 *
	 * @var callable
	 */
	protected $formRenderer = array('Phoenix\Form\Renderer\BootstrapRenderer', 'render');

	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getDefaultData()
	{
		$item = $this->getItem();

		if ($item->notNull())
		{
			return $item->dump();
		}

		return $this['form.data'];
	}

	/**
	 * getForm
	 *
	 * @param string $definition
	 * @param string $control
	 * @param bool   $loadData
	 *
	 * @return Form
	 */
	public function getForm($definition = null, $control = null, $loadData = false)
	{
		$form = new Form($control);

		$form->defineFormFields($this->getFieldDefinition($definition));

		if ($loadData)
		{
			$data = $this->getDefaultData();

			$form->bind($data);
		}

		$form->setFieldRenderHandler($this->get('field.renderer', $this->formRenderer));

		Ioc::getDispatcher()->triggerEvent('onModelAfterGetForm', array(
			'form' => $form,
			'model' => $this
		));

		return $form;
	}

	/**
	 * getFieldDefinition
	 *
	 * @param string $definition
	 * @param string $name
	 *
	 * @return FieldDefinitionInterface
	 */
	public function getFieldDefinition($definition = null, $name = null)
	{
		$name = $name ? : $this->getName();

		$class = sprintf(
			'%s\Form\%s\%sDefinition',
			MvcHelper::getPackageNamespace($this, 2),
			ucfirst($name),
			ucfirst($definition)
		);

		if (!class_exists($class))
		{
			return new NullFiledDefinition;
		}

		return new $class;
	}

	/**
	 * validate
	 *
	 * @param   array  $data
	 *
	 * @return  boolean
	 *
	 * @throws  ValidFailException
	 */
	public function validate($data)
	{
		$form = $this->getForm('edit');

		$form->bind($data);

		if ($form->validate())
		{
			return true;
		}

		$errors = $form->getErrors();

		$msg = array(
			ValidateResult::STATUS_REQUIRED => array(),
			ValidateResult::STATUS_FAILURE => array()
		);

		foreach ($errors as $error)
		{
			$field = $error->getField();

			if ($error->getResult() == ValidateResult::STATUS_REQUIRED)
			{
				$msg[ValidateResult::STATUS_REQUIRED][] = Translator::sprintf('phoenix.message.validation.required', $field->getLabel() ? : $field->getName(false));
			}
			elseif ($error->getResult() == ValidateResult::STATUS_FAILURE)
			{
				$msg[ValidateResult::STATUS_FAILURE][] = Translator::sprintf('phoenix.message.validation.failure', $field->getLabel() ? : $field->getName(false));
			}
		}

		throw new ValidFailException($msg);
	}

	/**
	 * Method to get property FormRenderer
	 *
	 * @return  callable
	 */
	public function getFormRenderer()
	{
		return $this->formRenderer;
	}

	/**
	 * Method to set property formRenderer
	 *
	 * @param   callable $formRenderer
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFormRenderer($formRenderer)
	{
		$this->formRenderer = $formRenderer;

		return $this;
	}
}
