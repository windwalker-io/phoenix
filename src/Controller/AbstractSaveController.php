<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\FormAwareRepositoryInterface;
use Phoenix\Model\Traits\FormAwareRepositoryTrait;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\String\StringHelper;
use Windwalker\Utilities\ArrayHelper;

/**
 * The AbstractSaveController class.
 *
 * @since  1.0.5
 */
abstract class AbstractSaveController extends AbstractPostController
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
			$this->data = (array) $this->input->getArray($this->formControl);
		}
		else
		{
			$this->data = (array) $this->input->toArray();
		}
	}

	/**
	 * doSave
	 *
	 * @param DataInterface $data
	 *
	 * @return void
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 * @throws \Windwalker\Core\Model\Exception\ValidateFailException
	 * @throws \Windwalker\Record\Exception\NoResultException
	 */
	protected function doSave(DataInterface $data)
	{
		$data = $this->prepareStore($data);

		$this->validate($data);

		$this->getModel()->save($data);
	}

	/**
	 * doExecute
	 *
	 * @return mixed
	 * 
	 * @throws \Exception
	 * @throws \Throwable
	 */
	protected function doExecute()
	{
		// Get primary key from form data
		$pk = ArrayHelper::getValue((array) $this->data, $this->keyName);

		// If primary key not exists, this is a new record.
		$this->isNew = !$pk;

		$data = $this->getDataObject();

		$data->bind($this->data);

		// Process pre save hook, you may add your own logic in this method
		$this->preSave($data);

		// Just dave it.
		$this->doSave($data);

		// Process post save hook, you may add your own logic in this method
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
		return Translator::translate($this->langPrefix . 'message.save.success');
	}

	/**
	 * preSave
	 *
	 * @param DataInterface $data
	 *
	 * @return void
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
	}

	/**
	 * filter
	 *
	 * @param DataInterface|Entity $data
	 *
	 * @return  DataInterface|Entity
	 */
	protected function prepareStore(DataInterface $data)
	{
		$model = $this->getModel();

		if ($model instanceof FormAwareRepositoryInterface)
		{
			$result = $model->prepareStore($data->dump(true));

			return $data->bind($result, true);
		}

		return $data;
	}

	/**
	 * validate
	 *
	 * @param   DataInterface|Entity  $data
	 *
	 * @return  void
	 *
	 * @throws ValidateFailException
	 */
	protected function validate(DataInterface $data)
	{
		$model = $this->getModel();

		if ($model instanceof FormAwareRepositoryTrait)
		{
			$model->validate($data->dump(true));
		}
	}

	/**
	 * getFailRedirect
	 *
	 * @param  DataInterface|Entity $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(DataInterface $data = null)
	{
		$pk = $this->getDataObject()->{$this->keyName};

		return $this->router->route($this->getName(), $this->getRedirectQuery(array($this->keyName => $pk)));
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param  DataInterface|Entity $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(DataInterface $data = null)
	{
		$data = $data ? : $this->getDataObject();

		switch ($this->task)
		{
			case 'save2close':
				return $this->router->route($this->config['list_name'], $this->getRedirectQuery());

			case 'save2new':
				return $this->router->route($this->getName(), $this->getRedirectQuery(array('new' => '')));

			case 'save2copy':
				$data->{$this->keyName} = null;

				if ($data->title)
				{
					$data->title = StringHelper::increment($data->title);
				}

				if ($data->alias)
				{
					$data->alias = StringHelper::increment($data->alias, StringHelper::INCREMENT_STYLE_DASH);
				}

				$this->setUserState($this->getContext('edit.data'), $data->dump(true));

				return $this->router->route($this->getName(), $this->getRedirectQuery());

			default:
				$pk = $data->{$this->keyName};

				return $this->router->route($this->getName(), $this->getRedirectQuery(array($this->keyName => $pk)));
		}
	}
}
