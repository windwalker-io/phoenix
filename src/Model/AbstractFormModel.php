<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Data\Data;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Ioc;
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
			$pk = $pk ? : $state['item.id'];

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
	 * @param bool $loadData
	 *
	 * @return Form
	 */
	public function getForm($loadData = false)
	{
		$form = new Form($this->getName());

		$form->defineFormFields($this->getFieldDefinition());

		if ($loadData)
		{
			$session = Ioc::getSession();

			$data = $session->get($this->getName() . '.edit.data' . $this['item.id']);
			$data = $data ? : $this->getItem();

			$form->bind($data);
		}

		return $form;
	}

	/**
	 * getFieldDefinition
	 *
	 * @return  FieldDefinitionInterface
	 */
	abstract protected function getFieldDefinition();

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