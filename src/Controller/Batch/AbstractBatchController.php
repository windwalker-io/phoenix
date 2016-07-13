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
	protected $pks = array();

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
	 * @return  boolean
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

		$this->preSave($data);

		foreach ((array) $this->pks as $pk)
		{
			$this->save($pk, clone $data);
		}

		$this->postSave($data);

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
	 * cleanData
	 *
	 * @param Data $data
	 *
	 * @return  Data
	 */
	protected function cleanData(Data $data)
	{
		// Remove empty data
		foreach ($data as $k => $value)
		{
			if ((string) $value === '')
			{
				unset($data[$k]);
			}
		}

		return $data;
	}

	/**
	 * validate
	 *
	 * @param Data $data
	 *
	 * @return  void
	 *
	 * @throws  ValidateFailException
	 */
	protected function validate(Data $data)
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
		$name = $name ? : $this->config['item_name'];

		return parent::getModel($name, $source, $forceNew);
	}
}
