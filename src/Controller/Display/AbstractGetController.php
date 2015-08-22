<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Controller\AbstractRadController;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\View\BladeHtmlView;
use Windwalker\Core\View\HtmlView;

/**
 * The AbstractGetController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class AbstractGetController extends AbstractRadController
{
	/**
	 * Property model.
	 *
	 * @var  DatabaseModel
	 */
	protected $model;

	/**
	 * Property view.
	 *
	 * @var  BladeHtmlView
	 */
	protected $view;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->model = $this->getModel();
		$this->view = $this->getView();
	}


	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->view->setModel($this->model);

		$this->assignModels($this->view);

		return $this->view->render();
	}

	/**
	 * assignModels
	 *
	 * @param HtmlView $view
	 *
	 * @return  void
	 */
	protected function assignModels(HtmlView $view)
	{
		// Implement it.
	}
}
