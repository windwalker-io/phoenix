<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Phoenix\Form\NullFiledDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\Data\Data;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Form\Validate\ValidateResult;
use Windwalker\Record\Record;

/**
 * The AbstractFormModel class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class FormModel extends PhoenixModel
{
	/**
	 * getItem
	 *
	 * @param   mixed  $pk
	 *
	 * @return  Data
	 */
	public function getItem($pk = null)
	{
		$state = $this->state;

		return $this->fetch('item', function() use ($pk, $state)
		{
			$pk = $pk ? : $state['item.pk'];

			if (!$pk)
			{
				return new Data;
			}

			$item = $this->getRecord();

			try
			{
				$item->load($pk);
			}
			catch (\RuntimeException $e)
			{
				return new Data;
			}

			$this->postGetItem($item);

			return new Data($item->toArray());
		});
	}

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
	 * postGetItem
	 *
	 * @param Record $item
	 *
	 * @return  void
	 */
	protected function postGetItem(Record $item)
	{
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

		$form->setFieldRenderHandler($this->get('field.renderer', array('Phoenix\Form\Renderer\BootstrapRenderer', 'render')));

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
}
