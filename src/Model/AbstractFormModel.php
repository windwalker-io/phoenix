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
abstract class AbstractFormModel extends DatabaseModel
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
	 * getRecord
	 *
	 * @param   string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null)
	{
		$name = $name ? : $this->getName();

		$class = sprintf('%s\Record\%sRecord', ReflectionHelper::getNamespace($this), ucfirst($name));

		if (!class_exists($class))
		{
			throw new \DomainException($class . ' not exists.');
		}

		return new $class($this->db);
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

	/**
	 * reorder
	 *
	 * @param array $conditions
	 *
	 * @return  boolean
	 */
	public function reorder($conditions = array())
	{
		$mapper = $this->getDataMapper();

		$dataset = $mapper->find($conditions);

		$ordering = $dataset->ordering;

		asort($ordering);

		$i = 1;

		foreach ($ordering as $k => $order)
		{
			$dataset[$k]->ordering = $i;

			$i++;
		}

		$mapper->update($dataset);

		return true;
	}

	/**
	 * getMaxOrdering
	 *
	 * @param array $conditions
	 *
	 * @return  int
	 */
	public function getMaxOrdering($conditions = array())
	{
		$query = $this->db->getQuery(true)
			->select(sprintf('MAX(%s)', 'ordering'))
			->from($this->getDefaultTable());

		// Condition should be an array.
		if (count($conditions))
		{
			QueryHelper::buildWheres($query, $conditions);
		}

		return $this->db->setQuery($query)->loadResult();
	}
}
