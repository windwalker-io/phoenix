<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Phoenix\Model\Filter;

use Windwalker\Query\Query;
use Windwalker\Query\QueryElement;

/**
 * Search Helper
 *
 * @since 2.0
 */
class SearchHelper extends AbstractFilterHelper
{
	/**
	 * Property fuzzySearching.
	 *
	 * @var  bool
	 */
	protected $fuzzySearching = true;

	/**
	 * Execute the filter and add in query object.
	 *
	 * @param   Query  $query    Db query object.
	 * @param   array  $searches The data from request.
	 *
	 * @return  Query Return the query object.
	 */
	public function execute(Query $query, $searches = array())
	{
		$searchValue = array();

		foreach ($searches as $field => $value)
		{
			// If handler is FALSE, means skip this field.
			if (array_key_exists($field, $this->handler) && $this->handler[$field] === static::SKIP)
			{
				continue;
			}

			if (!empty($this->handler[$field]) && is_callable($this->handler[$field]))
			{
				$condition = call_user_func($this->handler[$field], $query, $field, $value);
			}
			else
			{
				$handler = $this->getDefaultHandler();

				/** @see SearchHelper::getDefaultHandler() */
				$condition = $handler($query, $field, $value);
			}

			if ($condition)
			{
				$searchValue[] = $condition;
			}
		}

		if (count($searchValue))
		{
			$query->where(new QueryElement('()', $searchValue, " \nOR "));
		}

		return $query;
	}

	/**
	 * Register the default handler.
	 *
	 * @return  callable The handler callback.
	 */
	protected function getDefaultHandler()
	{
		if ($this->fuzzySearching)
		{
			/**
			 * Default handler closure.
			 *
			 * @param   Query   $query  The query object.
			 * @param   string  $field  The field name.
			 * @param   string  $value  The filter value.
			 *
			 * @return  string
			 */
			$handler = function(Query $query, $field, $value)
			{
				$values = [];
				$searchValues = [];

				if (is_string($value))
				{
					$values = explode(' ', $value);
				}

				$values = array_filter(array_map('trim', (array) $values), 'strlen');

				foreach ($values as $val)
				{
					if ($val && $field != '*')
					{
						if ((string) $val !== '')
						{
							$searchValues[] = $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $val . '%');
						}
					}
				}

				return implode($searchValues, "\nOR ");
			};
		}
		else
		{
			/**
			 * Default handler closure.
			 *
			 * @param   Query   $query  The query object.
			 * @param   string  $field  The field name.
			 * @param   string  $value  The filter value.
			 *
			 * @return  string
			 */
			$handler = function(Query $query, $field, $value)
			{
				if ($value && $field != '*')
				{
					if ((string) $value !== '')
					{
						return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
					}
				}

				return null;
			};
		}

		return $handler;
	}

	/**
	 * Method to set property fuzzySearching
	 *
	 * @param   bool $bool
	 *
	 * @return  bool|static  Return self to support chaining.
	 */
	public function fuzzySearching($bool = null)
	{
		if ($bool === null)
		{
			return $this->fuzzySearching;
		}

		$this->fuzzySearching = (bool) $bool;

		return $this;
	}
}
