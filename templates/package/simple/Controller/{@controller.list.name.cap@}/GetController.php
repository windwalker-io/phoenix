<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {@package.namespace@}{@package.name.cap@}\Controller\{@controller.list.name.cap@};

use {@package.namespace@}{@package.name.cap@}\Model\{@controller.list.name.cap@}Model;
use {@package.namespace@}{@package.name.cap@}\View\{@controller.list.name.cap@}\{@controller.list.name.cap@}HtmlView;
use Windwalker\Core\Controller\Controller;

/**
 * The GetController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class GetController extends Controller
{
	/**
	 * Property model.
	 *
	 * @var  {@controller.list.name.cap@}Model
	 */
	protected $model;

	/**
	 * Property view.
	 *
	 * @var  {@controller.list.name.cap@}HtmlView
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

		return $this->view->render();
	}
}
