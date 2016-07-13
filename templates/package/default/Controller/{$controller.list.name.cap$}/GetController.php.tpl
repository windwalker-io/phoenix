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
use Windwalker\Core\Controller\Middleware\JsonResponseMiddleware;
use Windwalker\Core\Model\ModelRepository;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class GetController extends ListDisplayController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.list.name.lower$}';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = '{$controller.item.name.lower$}';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = '{$controller.list.name.lower$}';

	/**
	 * Property model.
	 *
	 * @var  {$controller.list.name.cap$}Model
	 */
	protected $model;

	/**
	 * Property view.
	 *
	 * @var  {$controller.list.name.cap$}HtmlView
	 */
	protected $view;

	/**
	 * Property ordering.
	 *
	 * @var  string
	 */
	protected $defaultOrdering = null;

	/**
	 * Property direction.
	 *
	 * @var  string
	 */
	protected $defaultDirection = null;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->layout = $this->input->get('layout');
		$this->format = $this->input->get('format', 'html');

		parent::prepareExecute();

		if ($this->format == 'json')
		{
			$this->addMiddleware(JsonResponseMiddleware::class);
		}
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
		parent::prepareModelState($model);
	}
}
