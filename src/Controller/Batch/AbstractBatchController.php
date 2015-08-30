<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractDataHandlingController;
use Phoenix\Model\AbstractRadModel;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;

/**
 * The AbstractBatchController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AbstractBatchController extends AbstractDataHandlingController
{
	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = array();

	/**
	 * Property cid.
	 *
	 * @var  array
	 */
	protected $cid = array();

	/**
	 * Property useTransaction.
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
		$this->model = $this->getModel($this->config['item_name']);

		$this->cid = $this->input->getVar('cid');
	}

	/**
	 * doExecute
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		!$this->useTransaction or $this->model->transactionStart();

		try
		{
			$this->checkToken();

			if (!$this->data)
			{
				throw new \LogicException('Update data should not be empty');
			}

			$data = new Data($this->data);

			$model = $this->model;

			if (!$model instanceof AbstractRadModel)
			{
				throw new \LogicException('The model used for batch handling should be AbstractRadModel.');
			}

			$record = $model->getRecord($this->config['item_name']);

			foreach ((array) $this->cid as $id)
			{
				$record->load($id)
					->bind($this->data)
					->check()
					->store();

				$record->reset(true);
			}
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setRedirect($this->getFailRedirect($data), $e->getMessage(), 'warning');

			return false;
		}
		catch (\Exception $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect($data), $e->getMessage(), 'warning');

			return false;
		}

		!$this->useTransaction or $this->model->transactionCommit();

		$this->setRedirect($this->getSuccessRedirect($data), 'Save success', 'success');

		return true;
	}
}
