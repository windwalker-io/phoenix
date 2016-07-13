<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\FormAwareRepositoryInterface;
use Phoenix\View\EditView;
use Windwalker\Core\Model\ModelRepository;

/**
 * The EditGetController class.
 *
 * @since  1.0.5
 */
class EditDisplayController extends ItemDisplayController
{
	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		if (!$this->model instanceof FormAwareRepositoryInterface)
		{
			throw new \LogicException(sprintf('Model: %s should be sub class of %s in %s.', get_class($this->model), FormAwareRepositoryInterface::class, __CLASS__));
		}

		if (!$this->view instanceof EditView)
		{
			throw new \LogicException(sprintf('View: %s should be sub class of %s in %s.', get_class($this->view), EditView::class, __CLASS__));
		}
	}

	/**
	 * prepareExecute
	 *
	 * @param ModelRepository $model
	 *
	 * @return void
	 */
	protected function prepareModelState(ModelRepository $model)
	{
		parent::prepareModelState($model);

		if ($this->input->get('new') !== null)
		{
			$this->removeUserState($this->getContext('edit.data'));
		}

		$model['form.data'] = $this->getUserState($this->getContext('edit.data'));

		$this->removeUserState($this->getContext('edit.data'));
	}
}
