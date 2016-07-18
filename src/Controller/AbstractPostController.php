<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Model\CrudModel;
use Phoenix\Model\CrudRepositoryInterface;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Http\Response\HtmlResponse;
use Windwalker\Http\Response\RedirectResponse;
use Windwalker\Record\Record;
use Windwalker\Uri\Uri;

/**
 * The AbstractAdminController class.
 *
 * @method  CrudModel  getModel($name = null, $source = null, $forceNew = false)
 *
 * @since  1.0
 */
abstract class AbstractPostController extends AbstractPhoenixController
{
	/**
	 * Property model.
	 *
	 * @var  CrudModel
	 */
	protected $model;

	/**
	 * Property data.
	 *
	 * @var  array
	 */
	protected $data = [];

	/**
	 * Property task.
	 *
	 * @var  string
	 */
	protected $task;

	/**
	 * Property record.
	 *
	 * @var  Record
	 */
	protected $record;

	/**
	 * Property keyName.
	 *
	 * @var  string
	 */
	protected $keyName = null;

	/**
	 * Property allowRedirectQuery.
	 *
	 * @var  array
	 */
	protected $redirectQueryFields = array(
		'return'
	);

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		parent::init();
	}

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		$this->model  = $this->getModel();
		$this->record = $this->getRecord();
		$this->task   = $this->input->get('task');

		// Determine model
		if (!$this->model instanceof CrudRepositoryInterface)
		{
			throw new \DomainException(sprintf('%s model should be instance of ' . CrudRepositoryInterface::class, $this->getName()));
		}

		// Determine the name of the primary key for the data.
		if (empty($this->keyName))
		{
			$this->keyName = $this->model->getKeyName() ? : 'id';
		}
	}

	/**
	 * processSuccess
	 *
	 * @param mixed  $result
	 *
	 * @return mixed
	 */
	public function processSuccess($result)
	{
		$this->removeUserState($this->getContext('edit.data'));

		$record = $this->getRecord();

		$this->addMessage($this->getSuccessMessage($record), Bootstrap::MSG_SUCCESS);

		$this->setRedirect($this->getSuccessRedirect($record));

		if (!$this->response instanceof HtmlResponse && !$this->response instanceof RedirectResponse)
		{
			return $this->record->dump(true);
		}
		
		return $result;
	}

	/**
	 * Process failure.
	 *
	 * @param \Exception $e
	 *
	 * @return bool
	 */
	public function processFailure(\Exception $e = null)
	{
		$this->setUserState($this->getContext('edit.data'), $this->data);

		$this->addMessage($e->getMessage(), Bootstrap::MSG_WARNING);

		$this->setRedirect($this->getFailRedirect($this->getRecord()));

		return false;
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
		return '';
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
		$uri = new Uri($this->app->uri->full);

		foreach ($this->getRedirectQuery() as $field => $value)
		{
			$uri->setVar($field, $value);
		}

		return $uri->toString();
	}

	/**
	 * getFailRedirect
	 *
	 * @param  DataInterface $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(DataInterface $data = null)
	{
		return $this->getSuccessRedirect($data);
	}

	/**
	 * getRedirectQuery
	 *
	 * @param array $query
	 *
	 * @return  array
	 */
	protected function getRedirectQuery($query = array())
	{
		foreach ((array) $this->redirectQueryFields as $field)
		{
			$query[$field] = $this->input->getString($field);
		}

		return $query;
	}

	/**
	 * Method to get property Data
	 *
	 * @return  array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * Method to set property data
	 *
	 * @param   array $data
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

	/**
	 * getRecord
	 *
	 * @param string $name
	 * @param bool   $forceNew
	 *
	 * @return Record
	 */
	public function getRecord($name = null, $forceNew = false)
	{
		if ($name)
		{
			return $this->getModel()->getRecord($name);
		}

		if (!$this->record instanceof Record || $forceNew)
		{
			$this->record = $this->getModel()->getRecord($this->record);
		}

		return $this->record;
	}
}
