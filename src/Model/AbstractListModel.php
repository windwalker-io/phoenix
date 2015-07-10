<?php
/**
 * Part of windspeaker project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\Pagination\Pagination;
use Windwalker\Data\DataSet;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\Ioc;
use Windwalker\Query\Query;

/**
 * The ListModel class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractListModel extends DatabaseModel
{
	/**
	 * Property allowFields.
	 *
	 * @var  array
	 */
	protected $allowFields = array();

	/**
	 * Property fieldMapping.
	 *
	 * @var  array
	 */
	protected $fieldMapping = array();

	/**
	 * Property queryHelper.
	 *
	 * @var  QueryHelper
	 */
	protected $queryHelper = null;

	/**
	 * prepareState
	 *
	 * @return  void
	 */
	protected function prepareState()
	{
	}

	/**
	 * getItems
	 *
	 * @return  DataSet
	 */
	public function getItems()
	{
		$cid = $this['cache.id'] ? : 'items';

		return $this->fetch($cid, function()
		{
			$this->prepareState();

			$query = $this->getListQuery($this->db->getQuery(true));

			$items = $this->getList($query, $this['list.start'], $this->get('list.limit'));

			return new DataSet($items);
		});
	}

	abstract protected function getListQuery(Query $query);

	/**
	 * getList
	 *
	 * @param Query    $query
	 * @param integer  $start
	 * @param integer  $limit
	 *
	 * @return  \stdClass[]
	 */
	public function getList(Query $query, $start = null, $limit = null)
	{
		$query->limit($limit, $start);

		if (WINDWALKER_DEBUG && $profiler = Ioc::get('phoenix.profiler'))
		{
			$profiler->mark(uniqid() . ' - ' . (string) $query->dump());
		}

		$select = $query->select;
		$select = str_replace('SELECT ', 'SQL_CALC_FOUND_ROWS ', $select);
		$query->clear('select')->select($select);

		return $this->db->getReader($query)->loadObjectList();
	}

	/**
	 * filterField
	 *
	 * @param string $field
	 * @param mixed  $default
	 *
	 * @return  string
	 */
	public function filterField($field, $default = null)
	{
		foreach ($this->allowFields as $allow)
		{
			if ($allow == $field)
			{
				return $field;
			}
		}

		return $default;
	}

	/**
	 * mapField
	 *
	 * @param string $field
	 * @param mixed  $default
	 *
	 * @return  string
	 */
	public function mapField($field, $default = null)
	{
		if (isset($this->fieldMapping[$field]))
		{
			return $this->fieldMapping[$field];
		}

		return $default;
	}

	/**
	 * Method to get property QueryHelper
	 *
	 * @return  QueryHelper
	 */
	public function getQueryHelper()
	{
		if (!$this->queryHelper)
		{
			$this->queryHelper = new QueryHelper($this->getDb());
		}

		return $this->queryHelper;
	}

	/**
	 * getPagination
	 *
	 * @param integer $total
	 *
	 * @return  Pagination
	 */
	public function getPagination($total = null)
	{
		$total = $total ? : $this->getTotal();

		return new Pagination($total, $this->get('list.page', 1), $this['list.limit']);
	}

	/**
	 * getTotal
	 *
	 * @return  integer
	 */
	public function getTotal()
	{
		return $this->fetch('total', function()
		{
			return $this->db->setQuery('SELECT FOUND_ROWS();')->loadResult();
		});
	}
}
