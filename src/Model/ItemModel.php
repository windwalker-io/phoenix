<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Data\Data;

/**
 * The AbstractFormModel class.
 * 
 * @since  1.0
 */
class ItemModel extends PhoenixModel
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
		$state = $this->state;

		$pk = $pk ? : $state['item.pk'];

		return $this->fetch('item.' . json_encode($pk), function() use ($pk, $state)
		{
			if (!$pk)
			{
				return new Data;
			}

			$item = $this->getRecord();

			try
			{
				$item->load($pk);
			}
			catch (\RuntimeException $e)
			{
				return new Data;
			}

			$item = new Data($item->dump());

			$this->postGetItem($item);

			return $item;
		});
	}

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
}
