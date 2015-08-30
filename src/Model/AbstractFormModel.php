<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Record\Record;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The AbstractFormModel class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractFormModel extends AbstractRadModel
{
	/**
	 * getDefaultData
	 *
	 * @return array
	 */
	public function getDefaultData()
	{
		return array();
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
	 * @param string $control
	 * @param bool   $loadData
	 * @param string $definition
	 *
	 * @return Form
	 */
	public function getForm($control = null, $loadData = false, $definition = null)
	{
		$form = new Form($control);

		$form->defineFormFields($this->getFieldDefinition($definition));

		if ($loadData)
		{
			$data = $this->getDefaultData();

			$form->bind($data);
		}

		return $form;
	}

	/**
	 * getFieldDefinition
	 *
	 * @param string $definition
	 *
	 * @return FieldDefinitionInterface
	 */
	abstract public function getFieldDefinition($definition = null);
}
