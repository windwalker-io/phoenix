<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Model\AdminModel;
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;
use Windwalker\Core\Language\Translator;
use Windwalker\Data\Data;

/**
 * The ReorderController class.
 *
 * @since  1.0.5
 */
abstract class AbstractReorderController extends AbstractBatchController
{
	use CsrfProtectionTrait;

	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'reorder';

	/**
	 * Property model.
	 *
	 * @var  AdminModel
	 */
	protected $model;

	/**
	 * Property orderField.
	 *
	 * @var  string
	 */
	protected $orderField = 'ordering';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->model  = $this->getModel($this->config['item_name']);
		$this->data   = $this->input->getVar('ordering', array());

		// Determine model
		if (!$this->model instanceof AdminModel)
		{
			throw new \UnexpectedValueException(sprintf('%s model need extend to AdminModel', $this->getName()));
		}
	}

	/**
	 * doExecute
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		$this->model['order.column'] = $this->orderField;

		$this->model->reorder((array) $this->data);

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
		return Translator::translate($this->langPrefix . 'message.batch.reorder.success');
	}
}
