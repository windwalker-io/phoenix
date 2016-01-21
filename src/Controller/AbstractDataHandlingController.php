<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\CrudModel;
use Windwalker\Data\Data;
use Windwalker\Record\Record;

/**
 * The AbstractAdminController class.
 *
 * @since  1.0
 */
abstract class AbstractDataHandlingController extends AbstractPhoenixController
{
	/**
	 * Property model.
	 *
	 * @var  CrudModel
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

		$this->model  = $this->getModel();
		$this->record = $this->getRecord();
		$this->task   = $this->input->get('task');

		// Determine model
		if (!$this->model instanceof CrudModel)
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

	/**
	 * getRecord
	 *
	 * @param string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = null)
	{
		if (!$this->model)
		{
			$this->model = $this->getModel();
		}

		$name = $name ? : $this->config['item_name'];

		return $this->model->getRecord($name);
	}
}
