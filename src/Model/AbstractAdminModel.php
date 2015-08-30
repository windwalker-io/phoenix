<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;
/**
 * The AbstractAdminModel class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractAdminModel extends AbstractCrudModel
{
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
