<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Data\DataSet;

/**
 * Interface ListRepositoryInterface
 *
 * @since  1.1
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
