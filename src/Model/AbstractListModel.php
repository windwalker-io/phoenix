<?php
/**
 * Part of windspeaker project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Phoenix\Form\NullFiledDefinition;
use Phoenix\Model\Filter\FilterHelper;
use Phoenix\Model\Filter\FilterHelperInterface;
use Phoenix\Model\Filter\SearchHelper;
use Windwalker\Core\Pagination\Pagination;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\Data\DataSet;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Query\Query;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The ListModel class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractListModel extends AbstractFormModel
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
	protected $queryHelper;

	/**
	 * Property filterHelper.
	 *
	 * @var  FilterHelperInterface
	 */
	protected $filterHelper;

	/**
	 * Property searchHelper.
	 *
	 * @var  FilterHelperInterface
	 */
	protected $searchHelper;

	/**
	 * prepareState
	 *
	 * @return  void
	 */
	protected function prepareState()
	{
	}

	/**
	 * configureTables
	 *
	 * @return  void
	 */
	abstract protected function configureTables();

	/**
	 * getAllowFields
	 *
	 * @return  array
	 */
	public function getAllowFields()
	{
		if ($this->hasCache('allow.fields'))
		{
			return $this->getCache('allow.fields');
		}

		$fields = $this->allowFields;

		$tables = $this->getQueryHelper()->getTables();

		foreach ($tables as $alias => $table)
		{
			foreach ($this->db->getTable($table['name'])->getColumns() as $column)
			{
				$fields[] = $alias . '.' . $column;
			}
		}

		return $this->setCache('allow.fields', array_unique($fields));
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
		$this->processFilters($query, (array) $this['list.filter']);

		// Build search query
		$this->processSearches($query, (array) $this['list.search']);

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
		if (in_array($field, $this->getAllowFields()))
		{
			return $field;
		}

		return $default;
	}

	/**
	 * filterFields
	 *
	 * @param  array $data
	 *
	 * @return  array
	 */
	public function filterDataFields(array $data)
	{
		$allowFields = $this->getAllowFields();

		$return = array();

		foreach ($data as $field => $value)
		{
			if (in_array($field, $allowFields))
			{
				$return[$field] = $value;
			}
		}

		return $return;
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

				$query->clear('select')->clear('order')->clear('limit')->select('COUNT(*)');

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
		$filters = $filters ? : $this->state->get('list.filter', array());

		$filters = $this->filterDataFields($filters);

		$filterHelper = $this->getFilterHelper();

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
		$searches = $searches ? : $this->state->get('list.search', array());

		$searches = $this->filterDataFields($searches);

		$searchHelper = $this->getSearchHelper();

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

	/**
	 * Method to get property FilterHelper
	 *
	 * @return  FilterHelper
	 */
	public function getFilterHelper()
	{
		if (!$this->filterHelper)
		{
			$this->filterHelper = new FilterHelper;
		}

		return $this->filterHelper;
	}

	/**
	 * Method to set property filterHelper
	 *
	 * @param   FilterHelperInterface $filterHelper
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFilterHelper(FilterHelperInterface $filterHelper)
	{
		$this->filterHelper = $filterHelper;

		return $this;
	}

	/**
	 * Method to get property SearchHelper
	 *
	 * @return  SearchHelper
	 */
	public function getSearchHelper()
	{
		if (!$this->searchHelper)
		{
			$this->searchHelper = new SearchHelper;
		}

		return $this->searchHelper;
	}

	/**
	 * Method to set property searchHelper
	 *
	 * @param   FilterHelperInterface $searchHelper
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setSearchHelper(FilterHelperInterface $searchHelper)
	{
		$this->searchHelper = $searchHelper;

		return $this;
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
			'%s\Field\%s\%s%sDefinition',
			MvcHelper::getPackageNamespace($this, 2),
			ucfirst($name),
			ucfirst($name),
			ucfirst($definition)
		);

		if (!class_exists($class))
		{
			return new NullFiledDefinition;
		}

		return new $class;
	}
}
