<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractPostController;
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;

/**
 * The AbstractBatchController class.
 *
 * @see  BatchDelegatingController
 *
 * @since  1.0.5
 */
abstract class AbstractBatchController extends AbstractPostController
{
	use CsrfProtectionTrait;

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
	 * Property allowNullData.
	 *
	 * @var  boolean
	 */
	protected $allowNullData = false;

	/**
	 * Property cid.
	 *
	 * @var  array
	 */
	protected $pks = [];

	/**
	 * Property emptyMark.
	 *
	 * @var  string
	 */
	protected $emptyMark = '__EMPTY__';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->pks = (array) $this->input->getArray($this->keyName, $this->input->getArray('id'));
	}

	/**
	 * save
	 *
	 * @param   string|int     $pk
	 * @param   DataInterface  $data
	 *
	 * @return  DataInterface
	 */
	protected function save($pk, DataInterface $data)
	{
		$data->{$this->keyName} = $pk;

		return $this->model->save($data);
	}

	/**
	 * doExecute
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		$data = new Data($this->data);

		$data = $this->cleanData($data);

		$this->checkAccess($data);

		if ($data->isNull() && !$this->allowNullData)
		{
			throw new ValidateFailException(Translator::translate('phoenix.message.batch.data.empty'));
		}

		if (count($this->pks) < 1)
		{
			throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.batch.item.empty'));
		}

		$this->validate($data);

		foreach ((array) $this->pks as $pk)
		{
			$temp = clone $data;

			$this->preSave($temp);

			$this->save($pk, $temp);

			$this->postSave($temp);
		}

		return true;
	}

	/**
	 * getSuccessMessage
	 *
	 * @param Data $data
	 *
	 * @return  string
	 */
	public function getSuccessMessage($data = null)
	{
		return Translator::plural($this->langPrefix . 'message.batch.' . $this->action . '.success', count($this->pks), count($this->pks));
	}

	/**
	 * preSave
	 *
	 * @param DataInterface $data
	 *
	 * @return  void
	 */
	protected function preSave(DataInterface $data)
	{

	}

	/**
	 * postSave
	 *
	 * @param DataInterface $data
	 *
	 * @return  void
	 */
	protected function postSave(DataInterface $data)
	{
		// Do some stuff
	}

	/**
	 * cleanData
	 *
	 * @param DataInterface $data
	 *
	 * @return  DataInterface
	 */
	protected function cleanData(DataInterface $data)
	{
		// Remove empty data
		foreach ($data as $k => $value)
		{
			if ((string) $value === '')
			{
				unset($data[$k]);
			}
			elseif ($value === $this->emptyMark)
			{
				$data[$k] = '';
			}
			elseif ($value === '\\' . $this->emptyMark)
			{
				$data[$k] = $this->emptyMark;
			}
		}

		return $data;
	}

	/**
	 * validate
	 *
	 * @param DataInterface $data
	 *
	 * @return  void
	 *
	 * @throws  ValidateFailException
	 */
	protected function validate(DataInterface $data)
	{
		// Do some stuff
	}

	/**
	 * getModel
	 *
	 * @param string $name
	 * @param mixed  $source
	 * @param bool   $forceNew
	 *
	 * @return ModelRepository
	 *
	 * @throws \DomainException
	 */
	public function getModel($name = null, $source = null, $forceNew = false)
	{
		// Force the singular model
		if ($name === null && !$this->model instanceof ModelRepository)
		{
			if (is_string($this->model))
			{
				$name = $this->model;
			}
			else
			{
				$name = $name ? : $this->config['item_name'];
			}
		}

		return parent::getModel($name, $source, $forceNew);
	}
}
