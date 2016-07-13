<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Windwalker\Data\Data;

/**
 * The DeleteController class.
 *
 * @since  1.0.5
 */
abstract class AbstractDeleteController extends AbstractBatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'delete';

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = array(
		'state' => -9
	);

	/**
	 * save
	 *
	 * @param   string|int $pk
	 * @param   Data       $data
	 *
	 * @return  mixed
	 */
	protected function save($pk, Data $data)
	{
		$data->{$this->keyName} = $pk;

		$this->model->delete($pk);
	}
}
