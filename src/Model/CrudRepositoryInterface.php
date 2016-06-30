<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Data\Data;
use Windwalker\DataMapper\Entity\Entity;

/**
 * The CrudModelInterface class.
 *
 * @since  {DEPLOY_VERSION}
 */
interface CrudRepositoryInterface
{
	/**
	 * getItem
	 *
	 * @param   mixed  $pk
	 *
	 * @return  Data|Entity
	 */
	public function getItem($pk = null);

	/**
	 * save
	 *
	 * @param Data|Entity $data
	 *
	 * @return  boolean
	 *
	 * @throws  \RuntimeException
	 */
	public function save(Data $data);

	/**
	 * delete
	 *
	 * @param array $pk
	 *
	 * @return  boolean
	 *
	 * @throws \UnexpectedValueException
	 */
	public function delete($pk = null);
}
