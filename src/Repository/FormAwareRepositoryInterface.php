<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Repository;

use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The FormModelInterface class.
 *
 * @since  1.1
 */
interface FormAwareRepositoryInterface
{
	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getFormDefaultData();

	/**
	 * getForm
	 *
	 * @param string|FieldDefinitionInterface $definition
	 * @param string                          $control
	 * @param bool                            $loadData
	 *
	 * @return Form
	 */
	public function getForm($definition = null, $control = null, $loadData = false);

	/**
	 * getFieldDefinition
	 *
	 * @param string $definition
	 * @param string $name
	 *
	 * @return FieldDefinitionInterface
	 */
	public function getFieldDefinition($definition = null, $name = null);

	/**
	 * filter
	 *
	 * @param array $data
	 *
	 * @return  array
	 */
	public function prepareStore($data);

	/**
	 * validate
	 *
	 * @param   array $data
	 * @param   Form  $form
	 *
	 * @return bool
	 * @throws ValidateFailException
	 */
	public function validate($data, Form $form = null);

	/**
	 * Method to get property FormRenderer
	 *
	 * @return  callable
	 */
	public function getFormRenderer();

	/**
	 * Method to set property formRenderer
	 *
	 * @param   callable $formRenderer
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFormRenderer($formRenderer);
}
