<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Model;

use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Data\Data;
use Windwalker\Database\Query\QueryHelper;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;
use Windwalker\Ioc;

/**
 * The AbstractFormModel class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractItemModel extends FormModel
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

		return $this->fetch('item', function() use ($pk, $state)
		{
			$pk = $pk ? : $state['item.id'];

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

			$this->postGetItem($item);

			return new Data($item->toArray());
		});
	}
}
