<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Data\Data;

/**
 * The AbstractAdminController class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractDataHandlingController extends AbstractRadController
{
	/**
	 * Property useTransition.
	 *
	 * @var  boolean
	 */
	protected $useTransaction = true;

	/**
	 * getFailRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data)
	{
		return $this->package->router->buildHttp($this->getName(), array('id' => $data->id));
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(Data $data)
	{
		return $this->package->router->buildHttp($this->getName(), array('id' => $data->id));
	}

	/**
	 * useTransaction
	 *
	 * @param   boolean  $bool
	 *
	 * @return  static|bool
	 */
	public function useTransaction($bool = null)
	{
		if ($bool === null)
		{
			return $this->useTransaction;
		}

		$this->useTransaction = (bool) $bool;

		return $this;
	}
}
