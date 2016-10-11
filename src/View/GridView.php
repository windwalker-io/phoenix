<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\View\Helper\GridHelper;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;

/**
 * The GridView class.
 *
 * @since  1.0
 */
class GridView extends ListView
{
	/**
	 * The fields mapper.
	 *
	 * @var  array
	 */
	protected $fields = array(
		'pk'          => 'id',
		'title'       => 'title',
		'alias'       => 'alias',
		'state'       => 'state',
		'ordering'    => 'ordering',
		'author'      => 'created_by',
		'author_name' => 'user_name',
		'created'     => 'created',
		'language'    => 'language',
		'lang_title'  => 'lang_title'
	);

	/**
	 * The grid config.
	 *
	 * @var  array
	 */
	protected $gridConfig = array();

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
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		$title = $title ? : Translator::sprintf('phoenix.title.grid', Translator::translate($this->langPrefix . $this->getName() . '.title'));

		return parent::setTitle($title);
	}

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		parent::prepareData($data);

		$data->form  = $data->form ? : $this->model->getForm('grid', null, true);

		$this->handleFilterBar($data);
		$this->handleModal($data);

		// Grid helper
		$data->grid = $this->getGridHelper();
	}

	/**
	 * handleFilterBar
	 *
	 * @param   Data  $data
	 *
	 * @return  void
	 */
	protected function handleFilterBar(Data $data)
	{
		// Widget
		$data->filterBar = $data->filterBar ? : WidgetHelper::createWidget('phoenix.grid.filterbar', 'edge', $this->package);
		$data->showFilterBar = false;

		// Handler filter bar
		foreach ((array) $data->state->get('input.filter') as $value)
		{
			if ($value !== '' && $value !== null)
			{
				$data->showFilterBar = true;

				break;
			}
		}
	}

	/**
	 * handleModal
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function handleModal(Data $data)
	{
		if ($this->getLayout() === 'modal')
		{
			// Should make this operation out of view
			$input = $this->package->app->input;

			$data->selector = $input->getString('selector');
			$data->function = $input->getString('function', 'Phoenix.Field.Modal.select');
		}
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
			$this->gridConfig['field'] = $this->configureFields();

			$this->gridHelper = new GridHelper($this, array_merge($this->gridConfig, $options));
		}

		return $this->gridHelper;
	}

	/**
	 * configureFields
	 *
	 * @param array $fields
	 *
	 * @return  array
	 */
	protected function configureFields($fields = null)
	{
		if ($fields && is_array($fields))
		{
			$this->fields = array_merge($this->fields, $fields);
		}

		return $this->fields;
	}
}
