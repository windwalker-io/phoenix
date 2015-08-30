<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Model\AbstractAdminModel;

/**
 * The ReorderController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ReorderController extends AbstractBatchController
{
	/**
	 * Property model.
	 *
	 * @var  AbstractAdminModel
	 */
	protected $model;

	/**
	 * Property orderField.
	 *
	 * @var  string
	 */
	protected $orderField = 'ordering';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->model  = $this->getModel($this->config['item_name']);
		$this->data   = $this->input->getVar('ordering', array());

		// Determine model
		if (!$this->model instanceof AbstractAdminModel)
		{
			throw new \UnexpectedValueException(sprintf('%s model need extend to AdminModel', $this->getName()));
		}
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
			if (!$this->checkToken())
			{
				throw new \RuntimeException('Invalid Token');
			}

			$this->model['order.column'] = $this->orderField;

			$this->model->reorder((array) $this->data);
		}
		catch (\Exception $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), 'warning');

			return false;
		}

		!$this->useTransaction or $this->model->transactionCommit();

		$this->setRedirect($this->getSuccessRedirect(), 'Reorder success', 'success');

		return true;
	}
}
