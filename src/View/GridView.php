<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\Document;
use Phoenix\Toolbar\Button\NewButton;
use Phoenix\Toolbar\Toolbar;
use Phoenix\View\Helper\GridHelper;
use Windwalker\Core\Widget\BladeWidget;
use Windwalker\Ioc;

/**
 * The GridView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class GridView extends ListView
{
	/**
	 * Property gridHelper.
	 *
	 * @var  GridHelper
	 */
	protected $gridHelper;

	/**
	 * Property orderField.
	 *
	 * @var  string
	 */
	protected $orderColumn = null;

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareRender($data)
	{
		$data->items      = $this->model->getItems();
		$data->pagination = $this->model->getPagination()->render($this->getPackage()->getName() . ':sakuras');
		$data->filterForm = $this->model->getForm('filter', null, true);
		$data->batchForm  = $this->model->getForm('batch', null, true);
		$data->state      = $this->model->getState();

		// Widget
		$data->filterBar = new BladeWidget('phoenix.grid.filterbar', $this->package->getName());

		// Grid
		$data->grid = $this->getGridHelper();

		Document::setTitle(ucfirst($this->getName()));
	}

	/**
	 * getGridHelper
	 *
	 * @param array $options
	 *
	 * @return  GridHelper
	 */
	public function getGridHelper($options = array())
	{
		if (!$this->gridHelper)
		{
			$defaultOptions = array(
				'order_column' => $this->orderColumn
			);

			$this->gridHelper = new GridHelper($this, array_merge($defaultOptions, $options));
		}

		return $this->gridHelper;
	}
}
