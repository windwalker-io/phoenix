<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Ioc;
use Windwalker\Query\Query;

/**
 * The ItemlistField class.
 *
 * @method $this table($value)
 * @method $this published($value)
 * @method $this stateField($value)
 * @method $this ordering($value)
 * @method $this select(mixed $value)
 * @method $this postQueryHandler(callable $callback)
 *
 * @since  1.0
 */
class ItemListField extends SqlListField
{
	/**
	 * Property table.
	 *
	 * @var  string
	 */
	protected $table;

	/**
	 * Property ordering.
	 *
	 * @var  string
	 */
	protected $ordering;

	/**
	 * getItems
	 *
	 * @return  \stdClass[]
	 */
	protected function getItems()
	{
		$db = Ioc::getDatabase();

		$query = $db->getQuery(true);
		$table = $this->get('table', $this->table);

		if (!$table)
		{
			return [];
		}

		if ($this->get('published'))
		{
			$query->where($query->quoteName($this->get('state_field', 'state')) . ' >= 1');
		}

		if ($ordering = $this->get('ordering', $this->ordering))
		{
			$query->order($ordering);
		}

		$select = $this->get('select', '*');

		$query->select($select)
			->from($table);

		$this->postQuery($query);

		$postQuery = $this->get('post_query', $this->get('postQuery'));

		if (is_callable($postQuery))
		{
			call_user_func($postQuery, $query, $this);
		}

		return (array) $db->setQuery($query)->loadAll();
	}

	/**
	 * postQuery
	 *
	 * @param Query $query
	 *
	 * @return  void
	 */
	protected function postQuery(Query $query)
	{
		//
	}

	/**
	 * getAccessors
	 *
	 * @return  array
	 *
	 * @since   3.1.2
	 */
	protected function getAccessors()
	{
		return array_merge(parent::getAccessors(), [
			'table',
			'published',
			'stateField' => 'state_field',
			'ordering',
			'select',
			'postQueryHandler' => 'post_query'
		]);
	}
}
