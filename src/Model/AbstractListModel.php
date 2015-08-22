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

	protected function configureTables()
	{
		// Implement it.
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

			$this->configureTables();

			$query = $this->getListQuery($this->db->getQuery(true));

			$items = $this->getList($query, $this['list.start'], $this->get('list.limit'));

			return new DataSet($items);
		});
	}

	/**
	 * getListQuery
	 *
	 * @param Query $query
	 *
	 * @return  Query
	 */
	protected function getListQuery(Query $query = null)
	{
		if ($this->hasCache('list.query'))
		{
			return $this->getCache('list.query');
		}

		$query = $query ? : $this->db->getQuery(true);

		$queryHelper = $this->getQueryHelper();

		// Prepare
		$this->prepareGetQuery($query);

		// Build filter query
		$this->processFilters($query, (array) $this['filter']);

		// Build search query
		$this->processSearches($query, (array) $this['search']);

		// Ordering
		$this->processOrdering($query);

		// Custom Where
		foreach ((array) $this['query.where'] as $k => $v)
		{
			$query->where($v);
		}

		// Custom Having
		foreach ((array) $this['query.having'] as $k => $v)
		{
			$query->having($v);
		}

		// Build query
		// ========================================================================

		// Get select columns
		$select = $this['query.select'];

		if (!$select)
		{
			$select = $queryHelper->getSelectFields();
		}

		$query->select($select);

		// Build Selected tables query
		$queryHelper->registerQueryTables($query);

		$this->postGetQuery($query);

		return $this->setCache('list.query', $query);
	}

	/**
	 * The prepare getQuery hook
	 *
	 * @param Query $query The db query object.
	 *
	 * @return  void
	 */
	protected function prepareGetQuery(Query $query)
	{
	}

	/**
	 * The post getQuery object.
	 *
	 * @param Query $query The db query object.
	 *
	 * @return  void
	 */
	protected function postGetQuery(Query $query)
	{
	}

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
	 * addTable
	 *
	 * @param string  $alias
	 * @param string  $table
	 * @param mixed   $condition
	 * @param string  $joinType
	 * @param boolean $prefix
	 *
	 * @return  static
	 */
	public function addTable($alias, $table, $condition = null, $joinType = 'LEFT', $prefix = null)
	{
		$this->getQueryHelper()->addTable($alias, $table, $condition, $joinType, $prefix);

		return $this;
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
		$self = $this;

		return $this->fetch('total', function() use ($self)
		{
			$query = $self->getListQuery();

			// Use fast COUNT(*) on Query objects if there no GROUP BY or HAVING clause:
			if ($query instanceof Query && $query->type == 'select'
				&& $query->group === null && $query->having === null)
			{
				$query = clone $query;

				$query->clear('select')->clear('order')->select('COUNT(*)');

				return (int) $this->db->setQuery($query)->loadResult();
			}

			// Otherwise fall back to inefficient way of counting all results.
			$this->db->setQuery($query);
			$this->db->execute();

			return (int) $this->db->getReader()->countAffected();
		});
	}

	/**
	 * Process the query filters.
	 *
	 * @param Query $query   The query object.
	 * @param array $filters The filters values.
	 *
	 * @return  Query The db query object.
	 */
	protected function processFilters(Query $query, $filters = array())
	{
		$filters = $filters ? : $this->state->get('filter', array());

		$filterHelper = $this->container->get('model.' . strtolower($this->name) . '.filter', Container::FORCE_NEW);

		$this->configureFilters($filterHelper);

		$query = $filterHelper->execute($query, $filters);

		return $query;
	}
	/**
	 * Configure the filter handlers.
	 *
	 * Example:
	 * ``` php
	 * $filterHelper->setHandler(
	 *     'sakura.date',
	 *     function($query, $field, $value)
	 *     {
	 *         $query->where($field . ' >= ' . $value);
	 *     }
	 * );
	 * ```
	 *
	 * @param FilterHelper $filterHelper The filter helper object.
	 *
	 * @return  void
	 */
	protected function configureFilters($filterHelper)
	{
		// Override this method.
	}
	/**
	 * Process the search query.
	 *
	 * @param Query  $query    The query object.
	 * @param array  $searches The search values.
	 *
	 * @return  Query The db query object.
	 */
	protected function processSearches(Query $query, $searches = array())
	{
		$searches = $searches ? : $this->state->get('search', array());

		$searchHelper = $this->container->get('model.' . strtolower($this->name) . '.search', Container::FORCE_NEW);

		$this->configureSearches($searchHelper);

		$query = $searchHelper->execute($query, $searches);

		return $query;
	}

	/**
	 * Configure the search handlers.
	 *
	 * Example:
	 * ``` php
	 * $searchHelper->setHandler(
	 *     'sakura.title',
	 *     function($query, $field, $value)
	 *     {
	 *         return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
	 *     }
	 * );
	 * ```
	 *
	 * @param SearchHelper $searchHelper The search helper object.
	 *
	 * @return  void
	 */
	protected function configureSearches($searchHelper)
	{
		// Override this method.
	}

	/**
	 * Process ordering query.
	 *
	 * @param Query   $query      The query object.
	 * @param string  $ordering   The ordering string.
	 * @param string  $direction  ASC or DESC.
	 *
	 * @return  void
	 */
	protected function processOrdering(Query $query, $ordering = null, $direction = null)
	{
		$ordering  = $ordering  ? : $this->state->get('list.ordering'/* , $this->Viewitem . '.ordering'*/);

		// If no ordering set, ignore this function.
		if (!$ordering)
		{
			return;
		}

		$direction = $direction ? : $this->state->get('list.direction', 'ASC');

		$ordering  = explode(',', $ordering);

		// Add quote
		$ordering = array_map(
			function($value) use($query)
			{
				$value = explode(' ', trim($value));
				// $value[1] is direction
				if (isset($value[1]))
				{
					return $query->quoteName($value[0]) . ' ' . $value[1];
				}
				return $query->quoteName($value[0]);
			},
			$ordering
		);

		$ordering = implode(', ', $ordering);

		$query->order($ordering . ' ' . $direction);
	}
}
