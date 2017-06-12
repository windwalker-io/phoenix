<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Model\{$controller.list.name.cap$}Model;
use {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$}\{$controller.list.name.cap$}HtmlView;
use Phoenix\Controller\Display\ListDisplayController;
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
class GetController extends ListDisplayController
{
	/**
	 * The default Model.
	 *
	 * If set model name here, controller will get model object by this name.
	 *
	 * @var  {$controller.list.name.cap$}Model
	 */
	protected $model = '{$controller.list.name.cap$}';

	/**
	 * Main View.
	 *
	 * If set view name here, controller will get model object by this name.
	 *
	 * @var  {$controller.list.name.cap$}HtmlView
	 */
	protected $view = '{$controller.list.name.cap$}';

	/**
	 * Property ordering.
	 *
	 * Please remember add table alias.
	 *
	 * @var  string
	 */
	protected $defaultOrdering = '{$controller.item.name.lower$}.id';

	/**
	 * Property direction.
	 *
	 * @var  string
	 */
	protected $defaultDirection = 'DESC';

	/**
	 * The list limit per page..
	 *
	 * Use 0 to set unlimited.
	 *
	 * @var integer
	 */
	protected $limit;

	/**
	 * Check user has access to view this page.
	 *
	 * Throw exception with 4xx code to block unauthorised access.
	 *
	 * @param   array|DataInterface  $data
	 *
	 * @return  boolean
	 *
	 * @throws \RuntimeException
	 * @throws RouteNotFoundException
	 * @throws UnauthorizedException
	 */
	public function checkAccess($data)
	{
		return true;
	}

	/**
	 * A hook before main process executing.
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->layout = $this->input->get('layout');
		$this->format = $this->input->get('format', 'html');

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
		 * @var $view  {$controller.list.name.cap$}HtmlView
		 * @var $model {$controller.list.name.cap$}Model
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
