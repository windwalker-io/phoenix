<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\AdminModel;
use Phoenix\Model\CrudModel;
use Phoenix\View\EditView;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\View\AbstractView;

/**
 * The EditGetController class.
 *
 * @method  AdminModel|CrudModel  getModel($name = null, $source = null, $forceNew = false)
 * @method  EditView              getView($name = null, $format = 'html', $engine = null, $forceNew = false)
 *
 * @since  1.0.5
 */
class EditDisplayController extends ItemDisplayController
{
	/**
	 * A hook before main process executing.
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();

		/*
		if (!$this->model instanceof FormAwareRepositoryInterface)
		{
			throw new \LogicException(sprintf('Model: %s should be sub class of %s in %s.', get_class($this->model), FormAwareRepositoryInterface::class, __CLASS__));
		}
		*/

		/* TODO: Check we need EditViewInterface or not.
		if (!$this->view instanceof EditView)
		{
			throw new \LogicException(sprintf('View: %s should be sub class of %s in %s.', get_class($this->view), EditView::class, __CLASS__));
		}*/
	}

	/**
	 * Prepare view and default model.
	 *
	 * You can configure default model state here, or add more sub models to view.
	 * Remember to call parent to make sure default model already set in view.
	 *
	 * @param AbstractView    $view  The view to render page.
	 * @param ModelRepository $model The default mode.
	 *
	 * @return  void
	 */
	protected function prepareViewModel(AbstractView $view, ModelRepository $model)
	{
		parent::prepareViewModel($view, $model);

		if ($this->input->get('new') !== null)
		{
			$this->removeUserState($this->getContext('edit.data'));
		}

		$model['form.data'] = $this->getUserState($this->getContext('edit.data'));

		$this->removeUserState($this->getContext('edit.data'));
	}
}
