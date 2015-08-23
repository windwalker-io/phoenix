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
		return $this->fetch('item', function() use ($pk)
		{
			$pk = $pk ? : $this['item.id'];

			if (!$pk)
			{
				return new Data;
			}

			$item = $this->getDataMapper()->findOne($pk);

			if (!$item)
			{
				return $item;
			}

			$this->postGetItem($item);

			return $item;
		});
	}

	/**
	 * getDataMapper
	 *
	 * @param   string $table
	 *
	 * @return  DataMapper
	 */
	public function getDataMapper($table = null)
	{
		$table = $table ? : $this->getDefaultTable();

		return new DataMapper($table);
	}

	/**
	 * getDefaultTable
	 *
	 * @return  string
	 */
	abstract public function getDefaultTable();

	/**
	 * postGetItem
	 *
	 * @param Data $item
	 *
	 * @return  void
	 */
	protected function postGetItem(Data $item)
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
