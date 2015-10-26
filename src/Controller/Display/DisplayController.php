<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Model\DatabaseModel;
use Windwalker\Core\Model\Model;
use Windwalker\Core\View\BladeHtmlView;
use Windwalker\Core\View\HtmlView;

/**
 * The AbstractGetController class.
 * 
 * @since  1.0
 */
class DisplayController extends AbstractPhoenixController
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
	 * Property format.
	 *
	 * @var  string
	 */
	protected $format;

	/**
	 * Property layout.
	 *
	 * @var  string
	 */
	protected $layout;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->format = $this->format ? : $this->app->get('route.extra.format', 'html');
		$this->layout = $this->layout ? : $this->app->get('route.extra.layout', 'default');

		$this->model = $this->getModel();
		$this->view = $this->getView(null, $this->format);
	}

	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->prepareUserState($this->model);

		$this->view->setModel($this->model);

		$this->assignModels($this->view);

		return $this->view->setLayout($this->layout)->render();
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

	/**
	 * prepareUserState
	 *
	 * @param   Model $model
	 *
	 * @return  void
	 */
	protected function prepareUserState(Model $model)
	{
	}
}
