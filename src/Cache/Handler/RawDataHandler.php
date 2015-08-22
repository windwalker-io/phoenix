<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Cache\Handler;

use Windwalker\Cache\DataHandler\DataHandlerInterface;

/**
 * The RawDataHandler class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class RawDataHandler implements DataHandlerInterface
{
	/**
	 * unserialize
	 *
	 * @param string $data
	 *
	 * @return  mixed
	 */
	public function encode($data)
	{
		return $data;
	}

	/**
	 * serialize
	 *
	 * @param mixed $data
	 *
	 * @return  string
	 */
	public function decode($data)
	{
		return $data;
	}
}
