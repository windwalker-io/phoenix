<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Controller;

use Phoenix\Model\AbstractFormModel;
use Phoenix\Session\CSRFToken;
use Windwalker\Core\Controller\Controller;
use Windwalker\Core\Ioc;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Data\Data;

/**
 * The AbstractSaveController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractSaveController extends AbstractDataHandlingController
{
	/**
	 * Property model.
	 *
	 * @var  \Windwalker\Core\Model\DatabaseModel
	 */
	protected $model;

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->model = $this->getModel();
		$this->data = $this->input->getVar(strtolower($this->getName()));
		$this->data['id'] = $this->input->get('id');
	}

	/**
	 * doExecute
	 *
	 * @return  mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		$session = \Windwalker\Ioc::getSession();
		$data = new Data($this->data);

		!$this->useTransaction or $this->model->transactionStart();

		try
		{
			$this->checkToken();

			$this->preSave($data);

			$this->validate($data);

			$this->doSave($data);

			$this->postSave($data);
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$session->set($this->getName() . '.edit.data' . $data->id, $this->data);

			$this->setRedirect($this->getFailRedirect($data), $e->getMessage(), 'warning');

			return false;
		}
		catch (\Exception $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$session->set($this->getName() . '.edit.data' . $data->id, $this->data);

			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect($data), $e->getMessage(), 'warning');

			return false;
		}

		!$this->useTransaction or $this->model->transactionCommit();

		$session->remove($this->getName() . '.edit.data' . $data->id);

		$this->setRedirect($this->getSuccessRedirect($data), 'Save success', 'success');

		return true;
	}

	/**
	 * postExecute
	 *
	 * @param mixed $result
	 *
	 * @return  mixed
	 */
	protected function postExecute($result = null)
	{
		return parent::postExecute($result);
	}

	/**
	 * preSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 */
	protected function preSave(Data $data)
	{
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 */
	abstract protected function doSave(Data $data);

	/**
	 * postSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function postSave(Data $data)
	{
	}

	/**
	 * validate
	 *
	 * @param   Data  $data
	 *
	 * @return  void
	 *
	 * @throws ValidFailException
	 */
	protected function validate(Data $data)
	{
		if ($this->model instanceof AbstractFormModel)
		{
			$form = $this->model->getForm();

			$form->bind($data);

			if (!$form->validate())
			{
				$errors = $form->getErrors();

				$msg = [];

				foreach ($errors as $error)
				{
					$msg[] = $error->getMessage();
				}

				throw new ValidFailException(implode('<br>', $msg));
			}
		}
	}
}
