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
			$value = (string) $value;

			// If handler is FALSE, means skip this field.
			if (array_key_exists($field, $this->handler) && $this->handler[$field] === static::SKIP)
			{
				continue;
			}

			if (!empty($this->handler[$field]) && is_callable($this->handler[$field]))
			{
				$condition = call_user_func_array($this->handler[$field], array($query, $field, $value));
			}
			else
			{
				$handler = $this->defaultHandler;

				/** @see SearchHelper::registerDefaultHandler() */
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
	protected function registerDefaultHandler()
	{
		/**
		 * Default handler closure.
		 *
		 * @param   Query   $query  The query object.
		 * @param   string  $field  The field name.
		 * @param   string  $value  The filter value.
		 *
		 * @return  Query
		 */
		return function(Query $query, $field, $value)
		{
			if ($value && $field != '*')
			{
				return $query->quoteName($field) . ' LIKE ' . $query->quote('%' . $value . '%');
			}

			return null;
		};
	}
}
