<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\AbstractListModel;
use Windwalker\Core\Model\Model;
use Windwalker\Filter\InputFilter;

/**
 * The ListGetController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ListGetController extends AbstractGetController
{
	/**
	 * Property model.
	 *
	 * @var  AbstractListModel
	 */
	protected $model;

	/**
	 * prepareUserState
	 *
	 * @param   Model  $model
	 *
	 * @return  void
	 */
	protected function prepareUserState(Model $model)
	{
		$model['list.limit'] = $this->app->get('list.limit', 15);
		$model['list.page']  = $this->getUserStateFromInput($this->getContext('list.page'), 'page', 1, InputFilter::INTEGER);
		$model['list.page']  = $model['list.page'] < 1 ? 1 : $model['list.page'];
		$model['list.start'] = ($model['list.page'] - 1) * $model['list.limit'];
	}
}
