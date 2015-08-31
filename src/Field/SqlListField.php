<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Form\Field\ListField;
use Windwalker\Html\Option;
use Windwalker\Ioc;

/**
 * The SqlListField class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SqlListField extends ListField
{
	/**
	 * prepareOptions
	 *
	 * @return  Option[]
	 */
	protected function prepareOptions()
	{
		$valueField = $this->get('value_field', 'id');
		$textField  = $this->get('text_field', 'title');
		$attribs    = $this->get('option_attribs', array());

		$items = $this->getItems();

		$options = array();

		foreach ($items as $item)
		{
			$value = isset($item->$valueField) ? $item->$valueField : null;
			$text  = isset($item->$textField)  ? $item->$textField : null;

			$level = !empty($item->level) ? $item->level - 1 : 0;

			if ($level < 0)
			{
				$level = 0;
			}

			$options[] = new Option(str_repeat('- ', $level) . $text, $value, $attribs);
		}

		return $options;
	}

	/**
	 * getItems
	 *
	 * @return  \stdClass[]
	 */
	protected function getItems()
	{
		$db = Ioc::getDatabase();

		$query = $this->get('query', $this->get('sql'));

		if (is_callable($query))
		{
			$handler = $query;

			$query = $db->getQuery(true);

			call_user_func($handler, $query, $this);
		}

		if (!$query)
		{
			return array();
		}

		return (array) $db->setQuery($query)->loadAll();
	}
}
