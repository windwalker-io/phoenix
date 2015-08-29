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
use Windwalker\Form\Form;

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
	 * Property ordering.
	 *
	 * @var  string
	 */
	protected $defaultOrdering = 'id';

	/**
	 * Property direction.
	 *
	 * @var  string
	 */
	protected $defaultDirection = 'DESC';

	/**
	 * prepareUserState
	 *
	 * @param   Model  $model
	 *
	 * @return  void
	 */
	protected function prepareUserState(Model $model)
	{
		// Pagination
		$model['list.limit'] = $this->app->get('list.limit', 15);
		$model['list.page']  = $this->getUserStateFromInput($this->getContext('list.page'), 'page', 1, InputFilter::INTEGER);
		$model['list.page']  = $model['list.page'] < 1 ? 1 : $model['list.page'];
		$model['list.start'] = ($model['list.page'] - 1) * $model['list.limit'];

		// Filter & Search
		$model['input.search'] = $this->getUserStateFromInput($this->getContext('list.search'), 'search', array(), InputFilter::ARRAY_TYPE);
		$model['input.filter'] = $this->getUserStateFromInput($this->getContext('list.filter'), 'filter', array(), InputFilter::ARRAY_TYPE);

		$model['list.search'] = $this->handleSearches($model['input.search']);
		$model['list.filter'] = $model['input.filter'];

		// Ordering
		$model['list.ordering'] = $this->getUserStateFromInput($this->getContext('list.ordering'), 'list_ordering', $this->defaultOrdering);
		$model['list.direction'] = $this->getUserStateFromInput($this->getContext('list.direction'), 'list_direction', $this->defaultDirection);
	}

	/**
	 * handleSearches
	 *
	 * @param   array  $search
	 *
	 * @return  array
	 */
	protected function handleSearches($search)
	{
		if (!isset($search['field']) || !isset($search['content']))
		{
			return array();
		}

		if ($search['field'] == '*' && isset($search['content']))
		{
			// Get search fields
			$form = new Form;
			$form->defineFormFields($this->model->getFieldDefinition('filter', $this->getName()));

			$searchField = $form->getField('field', 'search');

			if (!$searchField)
			{
				return array();
			}

			$options = $searchField->getOptions();

			$fields = array();

			foreach ($options as $option)
			{
				$fields[$option->getValue()] = $search['content'];
			}

			return $fields;
		}

		return array($search['field'] => $search['content']);
	}
}
