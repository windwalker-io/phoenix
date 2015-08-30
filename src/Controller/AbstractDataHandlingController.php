<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\AbstractCrudModel;
use Windwalker\Data\Data;
use Windwalker\Record\Record;

/**
 * The AbstractAdminController class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractDataHandlingController extends AbstractRadController
{
	/**
	 * Property model.
	 *
	 * @var  AbstractCrudModel
	 */
	protected $model;

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data;

	/**
	 * Property task.
	 *
	 * @var  string
	 */
	protected $task;

	/**
	 * Property record.
	 *
	 * @var  Record
	 */
	protected $record;

	/**
	 * Property pkName.
	 *
	 * @var  string
	 */
	protected $pkName = 'id';

	/**
	 * Property useTransition.
	 *
	 * @var  boolean
	 */
	protected $useTransaction = true;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->model  = $this->getModel($this->config['item_name']);
		$this->record = $this->model->getRecord($this->config['item_name']);
		$this->task   = $this->input->get('task');

		// Determine model
		if (!$this->model instanceof AbstractCrudModel)
		{
			throw new \UnexpectedValueException(sprintf('%s model need extend to CrudModel', $this->getName()));
		}

		// Determine the name of the primary key for the data.
		if (empty($this->pkName))
		{
			$this->pkName = $this->record->getKeyName();
		}
	}

	/**
	 * getFailRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data = null)
	{
		return $this->router->http($this->getName());
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(Data $data = null)
	{
		return $this->router->http($this->getName());
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
