<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

/**
 * The BatchController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UpdateController extends BatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'move';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->data = $this->input->getVar('batch', array());
	}
}
