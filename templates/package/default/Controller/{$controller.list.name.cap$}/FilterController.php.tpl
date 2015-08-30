<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use Phoenix\Controller\AbstractRadController;
use Windwalker\Filter\InputFilter;

/**
 * The FilterController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class FilterController extends AbstractRadController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::PLURAL;

	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$model['list.search'] = $this->getUserStateFromInput($this->getContext('list.search'), 'search', array(), InputFilter::ARRAY_TYPE);
		$model['list.filter'] = $this->getUserStateFromInput($this->getContext('list.filter'), 'filter', array(), InputFilter::ARRAY_TYPE);
		$model['list.ordering']  = $this->getUserStateFromInput($this->getContext('list.ordering'), 'list_ordering');
		$model['list.direction'] = $this->getUserStateFromInput($this->getContext('list.direction'), 'list_direction');

		$this->setRedirect($this->router->http($this->app->get('route.matched'), array('page' => $this->getUserState($this->getContext('list.page')))));

		return true;
	}
}
