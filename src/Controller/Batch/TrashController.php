<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

/**
 * The UnpublishController class.
 *
 * @since  1.0
 */
class TrashController extends BatchController
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
	protected $data = array(
		'state' => -1
	);
}
