<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\ListModel;
use Windwalker\Core\Model\Model;
use Windwalker\Filter\InputFilter;
use Windwalker\Form\Field\ListField;
use Windwalker\Form\Form;

/**
 * The ListGetController class.
 * 
 * @since  1.0
 */
class ListDisplayController extends DisplayController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::PLURAL;

	/**
	 * Property model.
	 *
	 * @var  ListModel
	 */
	protected $model;

	/**
	 * Property limit.
	 *
	 * @var  integer
	 */
	protected $limit;

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
	 * prepareUserState
	 *
	 * @param   Model  $model
	 *
	 * @return  void
	 */
	protected function prepareUserState(Model $model)
	{
		// Filter & Search
		$model['input.search'] = $this->getUserStateFromInput($this->getContext('list.search'), 'search', array(), InputFilter::ARRAY_TYPE);
		$model['input.filter'] = $this->getUserStateFromInput($this->getContext('list.filter'), 'filter', array(), InputFilter::ARRAY_TYPE);

		$model['list.search'] = $this->handleSearches($model['input.search']);
		$model['list.filter'] = $model['input.filter'];

		// Ordering
		$model['list.ordering'] = $this->getUserStateFromInput($this->getContext('list.ordering'), 'list_ordering', $this->defaultOrdering);
		$model['list.direction'] = $this->getUserStateFromInput($this->getContext('list.direction'), 'list_direction', $this->defaultDirection);

		// Pagination
		$model['list.limit'] = $this->limit === null ? $this->app->get('list.limit', 15) : $this->limit;
		$model['list.page']  = $this->getUserStateFromInput($this->getContext('list.page'), 'page', 1, InputFilter::INTEGER);
		$model['list.page']  = $model['list.page'] < 1 ? 1 : $model['list.page'];
		$model['list.start'] = ($model['list.page'] - 1) * $model['list.limit'];
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

			/** @var ListField $searchField */
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
