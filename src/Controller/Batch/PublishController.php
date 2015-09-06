<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

/**
 * The PublishController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class PublishController extends BatchController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'publish';

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = array(
		'state' => 1
	);
}
