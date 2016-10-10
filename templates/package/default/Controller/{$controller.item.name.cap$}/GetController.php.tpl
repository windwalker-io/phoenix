<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.item.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Model\{$controller.item.name.cap$}Model;
use {$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$}\{$controller.item.name.cap$}HtmlView;
use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class GetController extends EditDisplayController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.item.name.cap$}';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = '{$controller.item.name.cap$}';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = '{$controller.list.name.cap$}';

	/**
	 * Property model.
	 *
	 * @var  {$controller.item.name.cap$}Model
	 */
	protected $model = '{$controller.item.name.cap$}';

	/**
	 * Property view.
	 *
	 * @var  {$controller.item.name.cap$}HtmlView
	 */
	protected $view = '{$controller.item.name.cap$}';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		parent::prepareExecute();
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
	}

	/**
	 * prepareViewData
	 *
	 * @param   AbstractView $view
	 *
	 * @return  void
	 */
	protected function prepareViewData(AbstractView $view)
	{
		parent::prepareViewData($view);
	}

	/**
	 * postExecute
	 *
	 * @param mixed $result
	 *
	 * @return  mixed
	 */
	protected function postExecute($result = null)
	{
		return parent::postExecute($result);
	}
}
