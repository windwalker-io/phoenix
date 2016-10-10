<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2014 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Phoenix\Model\Filter\FilterHelper;
use Phoenix\Model\Filter\FilterHelperInterface;
use Phoenix\Model\Filter\SearchHelper;
use Phoenix\Model\Traits\FormAwareRepositoryTrait;
use Windwalker\Core\Model\DatabaseModelRepository;
use Windwalker\Core\Pagination\Pagination;
use Windwalker\Data\DataSet;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\Query\Query;
use Windwalker\Query\QueryInterface;
use Windwalker\Utilities\ArrayHelper;

/**
 * The ListModel class.
 *
 * @since  1.0
 */
class ListModel extends DatabaseModelRepository implements FormAwareRepositoryInterface
{
	use FormAwareRepositoryTrait;

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
	protected function configureTables()
	{
	}

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
	 * @throws \RuntimeException
	 */
	public function getItems()
	{
		// Make sure we change state values before cache detected.
		$this->prepareState();

		$cid = $this['cache.id'] ? : 'items';

		return $this->fetch($cid, function()
		{
			$query = $this->getListQuery($this->db->getQuery(true));

			$items = $this->getList($query, $this->getStart(), $this->get('list.limit'));

			return new DataSet($items);
		});
	}

	/**
	 * getDefaultData
	 *
	 * @return  array
	 */
	public function getFormDefaultData()
	{
		return array(
			'search' => $this['input.search'],
			'filter' => $this['input.filter']
		);
	}

	/**
	 * getListQuery
	 *
	 * @param Query $query
	 *
	 * @return  Query
	 * @throws \RuntimeException
	 */
	protected function getListQuery(Query $query = null)
	{
		if ($this->hasCache('list.query'))
		{
			return $this->getCache('list.query');
		}

		$query = $query ? : $this->db->getQuery(true);

		$queryHelper = $this->getQueryHelper(true);

		$this->configureTables();

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

		// Custom Binding
		foreach ((array) $this['query.bounded'] as $k => $v)
		{
			$query->bind(...$v);
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
	 * @param Query   $query
	 * @param integer $start
	 * @param integer $limit
	 *
	 * @return  \stdClass[]
	 * @throws \RuntimeException
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

		return ($default === null) ? $field : $default;
	}

	/**
	 * mapDataFields
	 *
	 * @param array $data
	 *
	 * @return  array
	 */
	public function mapDataFields(array $data)
	{
		$return = array();

		foreach ($data as $field => $value)
		{
			$return[$this->mapField($field)] = $value;
		}

		return $return;
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
	 * @param bool $new
	 *
	 * @return QueryHelper
	 */
	public function getQueryHelper($new = false)
	{
		if (!$this->queryHelper || $new)
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
	 * @return Pagination
	 */
	public function getPagination($total = null)
	{
		$total = $total !== null ? $total : $this->getTotal();

		return new Pagination($this->getPage() ? : 1, $this->getLimit(), $total);
	}

	/**
	 * getPagination
	 *
	 * @return Pagination
	 */
	public function getSimplePagination()
	{
		$this->config->set('list.fix_page', false);

		return (new Pagination($this->getPage() ? : 1, $this->getLimit(), -1, 1))->template('windwalker.pagination.simple');
	}

	/**
	 * getTotal
	 *
	 * @return  integer
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 */
	public function getTotal()
	{
		return $this->fetch('total', function()
		{
			$query = clone $this->getListQuery();

			// Use fast COUNT(*) on Query objects if there no GROUP BY or HAVING clause:
			if ($query instanceof Query && $query->type == 'select'
				&& $query->group === null && $query->having === null)
			{
				$query = clone $query;

				$query->clear('select')->clear('order')->clear('limit')->select('COUNT(*)');

				return (int) $this->db->setQuery($query)->loadResult();
			}

			// Otherwise fall back to inefficient way of counting all results.
			$subQuery = clone $query;

			$subQuery->clear('select')->clear('order')->clear('limit')->select('COUNT(*) AS ' . $query->quoteName('count'));

			$query = $this->db->getQuery(true);

			$query->select($query->format('COUNT(%n)', 'count'))
				->from($subQuery, 'c');

			$this->db->setQuery($query);

			return (int) $this->db->loadResult();
		});
	}

	/**
	 * getStart
	 *
	 * @return  integer
	 */
	public function getStart()
	{
		return $this->fetch('start', function()
		{
			$start = $this->state['list.start'];

			if ($this->config->get('list.fix_page', true))
			{
				$limit = $this->getLimit();
				$total = $this->getTotal();

				if ($total && $start > $total - $limit)
				{
					$page = (int) ceil($total / $limit);
					$start = max(0, ($page - 1) * $limit);

					$this->page($page);
				}
			}

			return $start;
		});
	}

	/**
	 * setLimit
	 *
	 * @param   integer  $limit
	 *
	 * @return  static
	 */
	public function limit($limit)
	{
		if ($limit < 0)
		{
			$limit = 0;
		}

		$this->set('list.limit', (int) $limit);

		return $this;
	}

	/**
	 * getLimit
	 *
	 * @return  integer
	 */
	public function getLimit()
	{
		return (int) $this->get('list.limit');
	}

	/**
	 * setLimit
	 *
	 * @param   integer $page
	 *
	 * @return  static
	 */
	public function page($page)
	{
		if ($page < 1)
		{
			$page = 1;
		}

		$this->set('list.page', (int) $page);

		return $this;
	}

	/**
	 * getLimit
	 *
	 * @return  integer
	 */
	public function getPage()
	{
		return (int) $this->get('list.page');
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

		$filters = static::flatten($filters, '.', '', 2);

		$filters = $this->filterDataFields($filters);
		$filters = $this->mapDataFields($filters);

		$filterHelper = $this->getFilterHelper(true);

		$this->configureFilters($filterHelper);

		return $filterHelper->execute($query, $filters);
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
	 * @param FilterHelperInterface $filterHelper The filter helper object.
	 *
	 * @return  void
	 */
	protected function configureFilters(FilterHelperInterface $filterHelper)
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

		$searches = static::flatten($searches, '.', '', 2);

		$searches = $this->filterDataFields($searches);
		$searches = $this->mapDataFields($searches);

		$searchHelper = $this->getSearchHelper(true);

		$this->configureSearches($searchHelper);

		return $searchHelper->execute($query, $searches);
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
	 * @param FilterHelperInterface $searchHelper The search helper object.
	 *
	 * @return  void
	 */
	protected function configureSearches(FilterHelperInterface $searchHelper)
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
		$ordering = $this->state->get('list.ordering', $ordering);

		$this->state->set('list.ordering', $ordering);

		// If no ordering set, ignore this function.
		if (!$ordering)
		{
			return;
		}

		$self = $this;

		$direction = $this->state->get('list.direction', $direction);

		$this->state->set('list.direction', $direction);

		$ordering  = explode(',', $ordering);

		// Add quote
		$ordering = array_map(
			function($value) use($query, $self)
			{
				// Remove extra spaces
				preg_replace('/\s+/', ' ', $value);

				$value = explode(' ', trim($value));

				// Check it is an allowed field.
				if (!$self->filterField($value[0]))
				{
					return '';
				}

				$field = $this->mapField($value[0]);

				if (!empty($field) && $field[strlen($field) - 1] != ')')
				{
					$field = $query->quoteName($field);
				}

				// $value[1] is direction
				if (isset($value[1]))
				{
					return $field . ' ' . $value[1];
				}

				return $field;
			},
			$ordering
		);

		$ordering = array_filter($ordering, 'strlen');

		$ordering = implode(', ', $ordering);

		if (!$ordering)
		{
			return;
		}

		$query->order($ordering . ' ' . $direction);
	}

	/**
	 * Method to get property FilterHelper
	 *
	 * @param bool $new
	 *
	 * @return FilterHelper
	 */
	public function getFilterHelper($new = false)
	{
		if (!$this->filterHelper || $new)
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
	 * @param bool $new
	 *
	 * @return SearchHelper
	 */
	public function getSearchHelper($new = false)
	{
		if (!$this->searchHelper || $new)
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
	 * addFilter
	 *
	 * @param   string  $key
	 * @param   mixed   $value
	 *
	 * @return  static
	 */
	public function addFilter($key, $value)
	{
		$this->set('list.filter.' . $key, $value);

		return $this;
	}

	/**
	 * addSearch
	 *
	 * @param   string  $key
	 * @param   mixed   $value
	 *
	 * @return  static
	 */
	public function addSearch($key, $value)
	{
		$this->set('list.search.' . $key, $value);

		return $this;
	}

	/**
	 * setOrdering
	 *
	 * @param  string      $order
	 * @param  bool|false  $direction
	 *
	 * @return  static
	 */
	public function ordering($order, $direction = false)
	{
		$this->set('list.ordering', $order);

		if ($direction !== false)
		{
			$this->set('list.direction', $direction);
		}

		return $this;
	}

	/**
	 * appendWhere
	 *
	 * @param   string|array $conditions
	 * @param   array        ...$args
	 *
	 * @return static
	 */
	public function where($conditions, ...$args)
	{
		foreach ((array) $conditions as $condition)
		{
			if ($args)
			{
				$condition = $this->db->getQuery(true)->format($condition, ...$args);
			}

			$this->state->push('query.where', $condition);
		}

		return $this;
	}

	/**
	 * Or Where.
	 *
	 * @param   mixed|callable   $conditions  A string, array of where conditions or callback to support logic.
	 *
	 * @return static
	 */
	public function orWhere($conditions)
	{
		$query = $this->db->getQuery(true);

		if (is_callable($conditions))
		{
			$conditions($query);

			$wheres = $query->where->getElements();

			foreach ($query->getBounded() as $key => $bound)
			{
				$this->bind($key, $bound->value, $bound->dataType, $bound->lengeh, $bound->driverOptions);
			}
		}
		else
		{
			$wheres = (array) $conditions;
			$wheres = ArrayHelper::flatten($wheres);
		}

		$wheres = (string) $query->element('()', $wheres, ' OR ');

		return $this->where($wheres);
	}

	/**
	 * Method to add a variable to an internal array that will be bound to a prepared SQL statement before query execution. Also
	 * removes a variable that has been bounded from the internal bounded array when the passed in value is null.
	 *
	 * @param   string|integer|array $key            The key that will be used in your SQL query to reference the value. Usually of
	 *                                               the form ':key', but can also be an integer.
	 * @param   mixed                &$value         The value that will be bound. The value is passed by reference to support output
	 *                                               parameters such as those possible with stored procedures.
	 * @param   integer              $dataType       Constant corresponding to a SQL datatype.
	 * @param   integer              $length         The length of the variable. Usually required for OUTPUT parameters.
	 * @param   array                $driverOptions  Optional driver options to be used.
	 *
	 * @return  static
	 *
	 * @since   3.0
	 */
	public function bind($key = null, $value = null, $dataType = \PDO::PARAM_STR, $length = 0, $driverOptions = array())
	{
		$this->state->push('query.bounded', [$key, $value, $dataType, $length, $driverOptions]);

		return $this;
	}

	/**
	 * appendHaving
	 *
	 * @param string|array  $conditions
	 * @param array         ...$args
	 *
	 * @return static
	 */
	public function having($conditions, ...$args)
	{
		foreach ((array) $conditions as $condition)
		{
			if ($args)
			{
				$condition = $this->db->getQuery(true)->format($condition, ...$args);
			}

			$this->state->push('query.having', $condition);
		}

		return $this;
	}

	/**
	 * appendWhereOr
	 *
	 * @param   mixed|callable $conditions
	 *
	 * @return  static
	 */
	public function orHaving($conditions)
	{
		$query = $this->db->getQuery(true);

		if (is_callable($conditions))
		{
			$conditions($query);

			$having = $query->having->getElements();

			foreach ($query->getBounded() as $key => $bound)
			{
				$this->bind($key, $bound->value, $bound->dataType, $bound->lengeh, $bound->driverOptions);
			}
		}
		else
		{
			$having = (array) $conditions;
			$having = ArrayHelper::flatten($having);
		}

		$having = $query->element('()', $having, ' OR ');

		return $this->having($having);
	}

	/**
	 * Method to recursively convert data to one dimension array.
	 *
	 * @param   array|object $array     The array or object to convert.
	 * @param   string       $separator The key separator.
	 * @param   string       $prefix    Last level key prefix.
	 * @param int            $level     Max level.
	 *
	 * @return array
	 */
	public static function flatten($array, $separator = '.', $prefix = '', $level = null)
	{
		$return = array();

		if ($array instanceof \Traversable)
		{
			$array = iterator_to_array($array);
		}
		elseif (is_object($array))
		{
			$array = get_object_vars($array);
		}

		foreach ($array as $k => $v)
		{
			$key = $prefix ? $prefix . $separator . $k : $k;

			if ((is_object($v) || is_array($v)) && ($level === null || $level > 1))
			{
				$return = array_merge($return, static::flatten($v, $separator, $key, $level === null ? $level : $level - 1));
			}
			else
			{
				$return[$key] = $v;
			}
		}

		return $return;
	}
}
