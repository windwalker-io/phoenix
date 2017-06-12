<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Model\{$controller.item.name.cap$}Model;
use {$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$}\{$controller.item.name.cap$}HtmlView;
use Phoenix\Controller\Display\DisplayController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\Core\View\AbstractView;
use Windwalker\Data\DataInterface;
use Windwalker\Router\Exception\RouteNotFoundException;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class GetController extends DisplayController
{
	/**
	 * The default Model.
	 *
	 * If set model name here, controller will get model object by this name.
	 *
	 * @var  {$controller.item.name.cap$}Model
	 */
	protected $model = '{$controller.item.name.cap$}';

	/**
	 * Main View.
	 *
	 * If set view name here, controller will get model object by this name.
	 *
	 * @var  {$controller.item.name.cap$}HtmlView
	 */
	protected $view = '{$controller.item.name.cap$}';

	/**
	 * Check user has access to view this page.
	 *
	 * Throw exception with 4xx code to block unauthorised access.
	 *
	 * @param   array|DataInterface $data
	 *
	 * @return  boolean
	 *
	 * @throws \RuntimeException
	 * @throws RouteNotFoundException
	 * @throws UnauthorizedException
	 */
	public function checkAccess($data)
	{
		return parent::checkAccess($data);
	}

	/**
	 * A hook before main process executing.
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();
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
		/**
		 * @var $view  {$controller.item.name.cap$}HtmlView
		 * @var $model {$controller.item.name.cap$}Model
		 */
		parent::prepareViewModel($view, $model);

		// Configure view and model here...
	}

	/**
	 * A hook after main process executing.
	 *
	 * @param mixed $result The result content to return, can be any value or boolean.
	 *
	 * @return  mixed
	 */
	protected function postExecute($result = null)
	{
		return parent::postExecute($result);
	}
}
