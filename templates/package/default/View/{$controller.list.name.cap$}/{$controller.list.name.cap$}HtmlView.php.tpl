<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$};

use Phoenix\Html\Document;
use Windwalker\Core\View\BladeHtmlView;
use Windwalker\Core\Widget\BladeWidget;

/**
 * The {$controller.list.name.cap$}HtmlView class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$controller.list.name.cap$}HtmlView extends BladeHtmlView
{
	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		$data->items      = $this->model->getItems();
		$data->pagination = $this->model->getPagination()->render($this->getPackage()->getName() . ':{$controller.list.name.lower$}');
		$data->filterForm = $this->model->getForm(null, true, 'filter');

		// Widget
		$data->filterBar = new BladeWidget('phoenix.grid.filterbar', $this->package->getName());

		Document::setTitle('{$controller.list.name.cap$}');
	}
}
