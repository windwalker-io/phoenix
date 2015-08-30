<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractDataHandlingController;
use Phoenix\Model\AbstractCrudModel;
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
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::PLURAL;

	/**
	 * Property cid.
	 *
	 * @var  array
	 */
	protected $pks = array();

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->pks = $this->input->getVar('cid');
	}

	/**
	 * save
	 *
	 * @param   string|int $pk
	 * @param   Data       $data
	 *
	 * @return  mixed
	 */
	protected function save($pk, Data $data)
	{
		$data->{$this->pkName} = $pk;

		$this->model->save($data);
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

			if (!$this->data)
			{
				throw new \LogicException('Update data should not be empty');
			}

			$data = new Data($this->data);

			foreach ((array) $this->pks as $pk)
			{
				$this->save($pk, $data);
			}
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), 'warning');

			return false;
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

		$this->setRedirect($this->getSuccessRedirect($data), 'Save success', 'success');

		return true;
	}
}
