<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

/**
 * The TrashController class.
 *
 * @since  1.0.5
 */
abstract class AbstractTrashController extends AbstractBatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'trash';

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = [
		'state' => -1
	];
}
