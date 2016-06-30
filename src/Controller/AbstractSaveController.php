<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\Traits\FormAwareRepositoryTrait;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Logger\Logger;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\Utilities\Debug\BacktraceHelper;
use Windwalker\Data\Data;
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
			$this->data = $this->input->getVar($this->formControl);
		}
		else
		{
			$this->data = $this->input->toArray();
		}
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 *
	 * @throws \RuntimeException
	 * @throws \InvalidArgumentException
	 * @throws \Windwalker\Core\Model\Exception\ValidateFailException
	 * @throws \Windwalker\Record\Exception\NoResultException
	 */
	protected function doSave(Data $data)
	{
		$data = $this->prepareStore($data);

		$this->validate($data);

		$this->model->save($data);
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
		$pk = ArrayHelper::getValue($this->data, $this->pkName);

		// If primary key not exists, this is a new record.
		$this->isNew = !$pk;

		if (!$this->isNew)
		{
			// If not new record, we load old data then override it by new data.
			$this->record->load($pk);
		}

		// Merge it into Record / Data object.
		$this->record->bind($this->data);

		// Process pre save hook, you may add your own logic in this method
		$this->preSave($this->record);

		// Just dave it.
		$this->doSave($this->record);

		// Process post save hook, you may add your own logic in this method
		$this->postSave($this->record);

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
	 * @param Data|Entity $data
	 *
	 * @return  Data
	 */
	protected function prepareStore(Data $data)
	{
		if ($this->model instanceof FormAwareRepositoryTrait)
		{
			$result = $this->model->prepareStore($data->dump(true));

			return $data->bind($result, true);
		}

		return $data;
	}

	/**
	 * validate
	 *
	 * @param   Data|Entity  $data
	 *
	 * @return  void
	 *
	 * @throws ValidateFailException
	 */
	protected function validate(Data $data)
	{
		if ($this->model instanceof FormAwareRepositoryTrait)
		{
			$this->model->validate($data->dump(true));
		}
	}

	/**
	 * getFailRedirect
	 *
	 * @param  Data|Entity $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data = null)
	{
		$pk = $data->{$this->pkName} ? : $this->model['item.pk'];

		return $this->router->route($this->getName(), $this->getRedirectQuery(array($this->pkName => $pk)));
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param  Data|Entity $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(Data $data = null)
	{
		$data = $data ? : new Entity;

		switch ($this->task)
		{
			case 'save2close':
				return $this->router->route($this->config['list_name'], $this->getRedirectQuery());

			case 'save2new':
				return $this->router->route($this->getName(), $this->getRedirectQuery(array('new' => '')));

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

				$this->setUserState($this->getContext('edit.data'), $data->dump(true));

				return $this->router->route($this->getName(), $this->getRedirectQuery());

			default:
				$pk = $this->model['item.pk'];

				return $this->router->route($this->getName(), $this->getRedirectQuery(array($this->pkName => $pk)));
		}
	}
}
