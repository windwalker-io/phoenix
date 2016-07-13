<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\Response\HtmlViewResponse;
use Windwalker\Core\View\AbstractView;
use Windwalker\Core\View\HtmlView;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Http\Response;

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
	 * @var  ModelRepository
	 */
	protected $model;

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

		// Prepare response
		$response = $this->response;

		$this->response = $this::createResponse(
			$this->format,
			$response->getBody()->__toString(),
			$response->getStatusCode(),
			$response->getHeaders()
		);
	}

	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->prepareModelState($this->model);

		// Add default
		$this->view->setModel($this->model, true);

		$this->assignModels($this->view);
		$this->prepareViewData($this->view);

		if ($this->view instanceof HtmlView)
		{
			$this->view->setLayout($this->layout);
		}
		elseif (class_exists(DebuggerHelper::class))
		{
			DebuggerHelper::disableConsole();
		}

		return $this->view->render();
	}

	/**
	 * getView
	 *
	 * @param string $name
	 * @param string $format
	 * @param string $engine
	 * @param bool   $forceNew
	 *
	 * @return AbstractView|HtmlView
	 *
	 * @throws \UnexpectedValueException
	 */
	public function getView($name = null, $format = null, $engine = null, $forceNew = false)
	{
		$format = $format ? : $this->format;

		return parent::getView($name, $format, $engine, $forceNew);
	}

	/**
	 * assignModels
	 *
	 * @param AbstractView $view
	 *
	 * @return  void
	 */
	protected function assignModels(AbstractView $view)
	{
		// Implement it.
	}

	/**
	 * prepareUserState
	 *
	 * @param   ModelRepository $model
	 *
	 * @return  void
	 */
	protected function prepareModelState(ModelRepository $model)
	{
	}

	/**
	 * prepareViewData
	 *
	 * @param   AbstractView  $view
	 *
	 * @return  void
	 */
	protected function prepareViewData(AbstractView $view)
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

	/**
	 * createResponse
	 *
	 * @param string $format
	 * @param array  ...$args
	 *
	 * @return  Response\AbstractContentTypeResponse
	 */
	public static function createResponse($format, ...$args)
	{
		switch (strtolower($format))
		{
			case 'html':
				return new HtmlViewResponse(...$args);
			case 'json':
				return new Response\JsonResponse(...$args);
			case 'xml':
				return new Response\XmlResponse(...$args);
		}

		return new Response\TextResponse(...$args);
	}
}
