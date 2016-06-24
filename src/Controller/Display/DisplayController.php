<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Model\Model;
use Windwalker\Core\View\HtmlView;

/**
 * The AbstractGetController class.
 * 
 * @since  1.0
 */
class DisplayController extends AbstractPhoenixController
{
	/**
	 * Default model.
	 *
	 * @var  Model
	 */
	protected $model;

	/**
	 * Property models.
	 *
	 * @var  Model[]
	 */
	protected $subModels = array();

	/**
	 * Property view.
	 *
	 * @var  HtmlView
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
		$this->layout = $this->layout ? : $this->app->get('route.extra.layout', $this->name);

		$this->model = $this->getModel($this->model);
		$this->view = $this->getView($this->view);
	}

	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->prepareUserState($this->model);

		// Add default
		$this->view->setModel($this->model, true);

		// Push sub models
		foreach ((array) $this->subModels as $name => $model)
		{
			$name = is_numeric($name) ? null : $name;

			$this->view->setModel($model, false, $name);
		}

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

	/**
	 * Method to get property Layout
	 *
	 * @return  string
	 */
	public function getLayout()
	{
		return $this->layout;
	}

	/**
	 * Method to set property layout
	 *
	 * @param   string $layout
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setLayout($layout)
	{
		$this->layout = $layout;

		return $this;
	}

	/**
	 * Method to get property Format
	 *
	 * @return  string
	 */
	public function getFormat()
	{
		return $this->format;
	}

	/**
	 * Method to set property format
	 *
	 * @param   string $format
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFormat($format)
	{
		$this->format = $format;

		return $this;
	}
}
