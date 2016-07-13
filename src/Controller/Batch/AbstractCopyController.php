<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Windwalker\Data\DataInterface;
use Windwalker\String\StringHelper;

/**
 * The CopyController class.
 *
 * @since  1.0.5
 */
abstract class AbstractCopyController extends AbstractBatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'copy';

	/**
	 * Property allowNullData.
	 *
	 * @var  boolean
	 */
	protected $allowNullData = true;

	/**
	 * Which fields should increment.
	 *
	 * @var array
	 */
	protected $incrementFields = array(
		'title' => StringHelper::INCREMENT_STYLE_DEFAULT,
		'alias' => StringHelper::INCREMENT_STYLE_DASH
	);

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->data = array_merge($this->input->getVar('batch', array()), (array) $this->data);
	}

	/**
	 * save
	 *
	 * @param int|string     $pk
	 * @param DataInterface  $data
	 *
	 * @return  boolean
	 */
	protected function save($pk, DataInterface $data)
	{
		// We load existing item first and bind data into it.
		$this->record->reset();

		$this->record->load($pk);

		$this->record->bind($data);

		$item = $this->record;

		$recordClone = $this->model->getRecord();

		$condition = array();

		// Check table has increment fields, default is title and alias.
		foreach ($this->incrementFields as $field => $type)
		{
			if ($this->record->hasField($field))
			{
				$condition[$field] = $item[$field];
			}
		}

		// Recheck item with same conditions(default is title & alias), if true, increment them.
		// If no item got, means it is the max number.
		do
		{
			$result = true;

			try
			{
				$recordClone->load($condition);

				foreach ($this->incrementFields as $field => $type)
				{
					if ($this->record->hasField($field))
					{
						$item[$field] = $condition[$field] = StringHelper::increment($item[$field], $type);
					}
				}
			}
			catch (\RuntimeException $e)
			{
				$result = false;
			}
		}
		while ($result);

		unset($item->{$this->keyName});

		return $this->model->save($item);
	}
}
