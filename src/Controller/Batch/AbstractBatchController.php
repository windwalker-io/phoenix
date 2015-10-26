<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractDataHandlingController;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;

/**
 * The AbstractBatchController class.
 *
 * @since  1.0.5
 */
abstract class AbstractBatchController extends AbstractDataHandlingController
{
	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'batch';

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

			$data = new Data($this->data);

			// Remove empty data
			foreach ($data as $k => $value)
			{
				if ((string) $value === '')
				{
					unset($data[$k]);
				}
			}

			$this->checkAccess($data);

			if ($data->isNull())
			{
				throw new ValidFailException(Translator::translate('phoenix.message.batch.error.empty'));
			}

			if (count($this->pks) < 1)
			{
				throw new ValidFailException(Translator::translate($this->langPrefix . '.message.' . $this->action . '.empty'));
			}

			$this->validate($data);

			$this->preSave($data);

			foreach ((array) $this->pks as $pk)
			{
				$this->save($pk, $data);
			}

			$this->postSave($data);
		}
		catch (ValidFailException $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), Bootstrap::MSG_WARNING);

			return false;
		}
		catch (\Exception $e)
		{
			!$this->useTransaction or $this->model->transactionRollback();

			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), Bootstrap::MSG_DANGER);

			return false;
		}

		!$this->useTransaction or $this->model->transactionCommit();

		$msg = Translator::plural($this->langPrefix . 'message.batch.' . $this->action . '.success', count($this->pks), count($this->pks));

		$this->setRedirect($this->getSuccessRedirect(), $msg, Bootstrap::MSG_SUCCESS);

		return true;
	}

	/**
	 * preSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function preSave(Data $data)
	{
		// Do some stuff
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
		// Do some stuff
	}

	/**
	 * validate
	 *
	 * @param Data $data
	 *
	 * @return  void
	 *
	 * @throws  ValidFailException
	 */
	protected function validate(Data $data)
	{
		// Do some stuff
	}
}
