<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Phoenix\Model\Traits\PhoenixDatabaseModelTrait;
use Windwalker\Core\Model\Model;
use Windwalker\Data\Data;

/**
 * The AbstractFormModel class.
 * 
 * @since  1.0
 */
class ItemModel extends Model implements PhoenixDatabaseModelInterface
{
	use PhoenixDatabaseModelTrait;

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
				return $this->getRecord();
			}

			$item = $this->getRecord();

			try
			{
				$item->load($pk);
			}
			catch (\RuntimeException $e)
			{
				return $item;
			}

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
