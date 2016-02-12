<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\FormModel;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;
use Windwalker\Form\Validate\ValidateResult;
use Windwalker\String\StringHelper;

/**
 * The AbstractSaveController class.
 *
 * @since  1.0.5
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
	 * Property isNew.
	 *
	 * @var  boolean
	 */
	protected $isNew = true;

	/**
	 * Property formControl.
	 *
	 * @var  string
	 */
	protected $formControl = 'item';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		if ($this->formControl)
		{
			$this->data = $this->input->getVar($this->formControl);
		}
		else
		{
			$this->data = $this->input->getArray();
		}
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
		$data = $this->filter($data);

		$this->validate($data);

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

		$this->isNew = !(bool) $data->{$this->pkName};

		!$this->useTransaction or $this->model->transactionStart();

		try
		{
			if (!$this->checkToken())
			{
				throw new \RuntimeException('Invalid Token');
			}

			$this->checkAccess($data);

			$this->preSave($data);

			$this->doSave($data);

			$this->postSave($data);
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setUserState($this->getContext('edit.data'), $this->data);

			$messages = $e->getMessages();

			if (isset($messages[ValidateResult::STATUS_REQUIRED]))
			{
				$this->addFlash((array) $messages[ValidateResult::STATUS_REQUIRED], Bootstrap::MSG_DANGER);

				unset($messages[ValidateResult::STATUS_REQUIRED]);
			}

			if (isset($messages[ValidateResult::STATUS_FAILURE]))
			{
				$this->addFlash((array) $messages[ValidateResult::STATUS_FAILURE], Bootstrap::MSG_WARNING);

				unset($messages[ValidateResult::STATUS_FAILURE]);
			}

			$this->setRedirect($this->getFailRedirect($data), $messages, Bootstrap::MSG_DANGER);

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

		$this->setRedirect($this->getSuccessRedirect($data), Translator::translate($this->langPrefix . 'message.save.success'), Bootstrap::MSG_SUCCESS);

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
	 * filter
	 *
	 * @param Data $data
	 *
	 * @return  Data
	 */
	protected function filter(Data $data)
	{
		if ($this->model instanceof FormModel)
		{
			$result = $this->model->filter($data->dump());

			return $data->bind($result);
		}

		return $data;
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
		if ($this->model instanceof FormModel)
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
	protected function getFailRedirect(Data $data = null)
	{
		$pk = $data->{$this->pkName} ? : $this->model['item.pk'];

		return $this->router->http($this->getName(), array($this->pkName => $pk));
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
		$data = $data ? : new Data;

		switch ($this->task)
		{
			case 'save2close':
				return $this->router->http($this->config['list_name']);

			case 'save2new':
				return $this->router->http($this->getName(), array('new' => ''));

			case 'save2copy':
				$data->{$this->pkName} = null;

				if ($data->title)
				{
					$data->title = StringHelper::increment($data->title);
				}

				if ($data->alias)
				{
					$data->alias = StringHelper::increment($data->alias, StringHelper::INCREMENT_STYLE_DASH);
				}

				$this->model->save($data);

				$pk = $this->model['item.pk'];

				return $this->router->http($this->getName(), array($this->pkName => $pk));

			default:
				$pk = $this->model['item.pk'];

				return $this->router->http($this->getName(), array($this->pkName => $pk));
		}
	}
}
