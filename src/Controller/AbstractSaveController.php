<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Controller;

use Phoenix\Model\AbstractCrudModel;
use Phoenix\Model\AbstractFormModel;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;

/**
 * The AbstractSaveController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractSaveController extends AbstractDataHandlingController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::SINGULAR;

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
	 * Property task.
	 *
	 * @var  string
	 */
	protected $task;

	/**
	 * Property pkName.
	 *
	 * @var  string
	 */
	protected $pkName = 'id';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->model = $this->getModel();
		$this->data = $this->input->getVar('item');
		$this->task = $this->input->get('task');
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 */
	protected function doSave(Data $data)
	{
		$this->validate($data);

		if (!$this->model instanceof AbstractCrudModel)
		{
			throw new \UnexpectedValueException('You have to use AbstractCrudModel to make a save operation.');
		}

		$this->model->save($data);
	}

	/**
	 * doExecute
	 *
	 * @return  mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		$data = new Data($this->data);

		!$this->useTransaction or $this->model->transactionStart();

		try
		{
			if (!$this->checkToken())
			{
				throw new \RuntimeException('Invalid Token');
			}

			$this->preSave($data);

			$this->doSave($data);

			$this->postSave($data);
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setUserState($this->getContext('edit.data'), $this->data);

			$this->setRedirect($this->getFailRedirect($data), $e->getMessages(), Bootstrap::MSG_DANGER);

			return false;
		}
		catch (\Exception $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setUserState($this->getContext('edit.data'), $this->data);

			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect($data), $e->getMessage(), Bootstrap::MSG_DANGER);

			return false;
		}

		!$this->useTransaction or $this->model->transactionCommit();

		$this->removeUserState($this->getContext('edit.data'));

		$this->setRedirect($this->getSuccessRedirect($data), 'Save success', Bootstrap::MSG_SUCCESS);

		return true;
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
			$this->model->validate($data->dump());
		}
	}

	/**
	 * getFailRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data)
	{
		$pk = $this->model['item.pk'];

		return $this->router->http($this->getName(), array($this->pkName => $pk));
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
		switch ($this->task)
		{
			case 'save2close':
				return $this->router->http($this->config['list_name']);

			case 'save2new':
				return $this->router->http($this->getName(), array('new' => ''));

			case 'save2copy':
				return $this->router->http($this->getName());

			default:
				$pk = $this->model['item.pk'];

				return $this->router->http($this->getName(), array($this->pkName => $pk));
		}
	}
}
