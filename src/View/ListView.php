<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\Document;
use Phoenix\View\Helper\GridHelper;
use Windwalker\Core\Widget\BladeWidget;

/**
 * The ListHtmlView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ListView extends AbstractRadHtmView
{
	/**
	 * Property gridHelper.
	 *
	 * @var  GridHelper
	 */
	protected $gridHelper;

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
		$data->filterForm = $this->model->getForm(null, true, 'filter');
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
			$this->gridHelper = new GridHelper($this, $options);
		}

		return $this->gridHelper;
	}
}
