<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Data\DataSet;

/**
 * Interface ListRepositoryInterface
 *
 * @since  {DEPLOY_VERSION}
 */
interface ListRepositoryInterface
{
	/**
	 * getItems
	 *
	 * @return  DataSet
	 * @throws \RuntimeException
	 */
	public function getItems();
}
