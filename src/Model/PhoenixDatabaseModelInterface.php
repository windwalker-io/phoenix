<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\DataMapper\DataMapper;
use Windwalker\Record\Record;

/**
 * The PhoenixDatabaseModelInterface class.
 *
 * @since  {DEPLOY_VERSION}
 */
interface PhoenixDatabaseModelInterface
{
	/**
	 * getRecord
	 *
	 * @param   string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null);

	/**
	 * getDataMapper
	 *
	 * @param string $name
	 *
	 * @return  DataMapper
	 */
	public function getDataMapper($name = null);
}
