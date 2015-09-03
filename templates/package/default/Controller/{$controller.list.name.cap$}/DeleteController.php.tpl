<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use Phoenix\Controller\Batch\AbstractBatchController;
use Windwalker\Data\Data;

/**
 * The DeleteController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class DeleteController extends AbstractBatchController
{
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
		$data->{$this->pkName} = $pk;

		$this->model->delete($pk);
	}
}
