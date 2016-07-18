<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View\Helper;

use Phoenix\Html\State\IconButton;
use Phoenix\Html\State\StateButton;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\View\HtmlView;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\Dom\HtmlElement;
use Windwalker\Structure\Structure;

/**
 * The GridHelper class.
 *
 * @property-read  HtmlView  $view
 * @property-read  Structure $config
 * @property-read  Structure $state
 * @property-read  Data      $current
 * @property-read  Data      $item
 * @property-read  integer   $row
 *
 * @since  1.0
 */
class GridHelper
{
	/**
	 * View instance.
	 *
	 * @var HtmlView
	 */
	protected $view;

	/**
	 * Config object.
	 *
	 * @var Structure
	 */
	protected $config = array();

	/**
	 * The fields mapper.
	 *
	 * @var array
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
	 * State object.
	 *
	 * @var Structure
	 */
	protected $state;

	/**
	 * The current item object.
	 *
	 * @var object
	 */
	protected $current;

	/**
	 * The row count.
	 *
	 * @var integer
	 */
	protected $row;

	/**
	 * Constructor.
	 *
	 * @param HtmlView $view   The view object.
	 * @param array    $config The config object.
	 */
	public function __construct(HtmlView $view, $config = array())
	{
		$this->view   = $view;
		$this->config = $config = ($config instanceof Structure) ? $config : new Structure($config);
		$this->state  = $state = $view['state'] ? : new Structure;

		// Merge fields
		$fields = $config->get('field');

		$fields = array_merge($this->fields, (array) $fields);

		$this->config->set('field', $fields);

		// Access context
		$this->context = $this->config->get('option') . '.' . $this->config->get('view_item');

		// Ordering
		$listOrder   = $state->get('list.ordering');
		$orderColumn = $state->get('list.orderColumn', $config->get('order_column'));
		$listDirn    = $this->state->get('list.direction');

		$state->set('list.saveorder', ($listOrder == $orderColumn) && strtoupper($listDirn) === 'ASC');
	}

	/**
	 * Method to sort a column in a grid
	 *
	 * @param   string  $label  The link title
	 * @param   string  $field  The order field for the column
	 *
	 * @return  string
	 */
	public function sortTitle($label, $field)
	{
		$listOrder = $this->state->get('list.ordering');
		$listDirn  = $this->state->get('list.direction');
		$selector  = $this->config->get('form_selector', '#admin-form');

		return WidgetHelper::render('phoenix.grid.table.sort', array(
			'label'     => $label,
			'field'     => $field,
			'ordering'  => $listOrder,
			'direction' => strtoupper($listDirn),
			'selector'  => $selector
		), WidgetHelper::EDGE);
	}

	/**
	 * Set current item for this loop.
	 *
	 * @param object  $item The item object.
	 * @param integer $i    The row number.
	 *
	 * @return GridHelper Return self to support chaining.
	 */
	public function setItem($item, $i)
	{
		if (!($item instanceof Data))
		{
			$item = new Data($item);
		}

		$this->row = (int) $i;

		$this->current = $item;

		return $this;
	}

	/**
	 * Method to get property Current
	 *
	 * @return  object
	 */
	public function getItem()
	{
		return $this->current;
	}

	/**
	 * orderButton
	 *
	 * @return  string
	 */
	public function orderButton()
	{
		$keyName = $this->config->get('field.pk');
		$orderField = $this->config['field.ordering'];
		$saveOrder = $this->state->get('list.saveorder');

		return WidgetHelper::render('phoenix.grid.table.order-button', array(
			'item'   => $this->current,
			'row'    => $this->row,
			'keyName' => $keyName,
			'orderField'  => $orderField,
			'saveOrder' => $saveOrder
		), WidgetHelper::EDGE);
	}

	/**
	 * saveOrderButton
	 *
	 * @return  string
	 */
	public function saveOrderButton()
	{
		if ($this->state->get('list.saveorder'))
		{
			return WidgetHelper::render('phoenix.grid.table.saveorder-button', array(), WidgetHelper::EDGE);
		}

		return '';
	}

	/**
	 * checkboxesToggle
	 *
	 * @param array $options
	 *
	 * @return string
	 */
	public function checkboxesToggle($options = array())
	{
		$options['duration'] = isset($options['duration']) ? $options['duration'] : 0;

		return WidgetHelper::render('phoenix.grid.table.checkboxes-toggle', array('options' => $options), WidgetHelper::EDGE);
	}

	/**
	 * Checkbox input.
	 *
	 * @return  string Checkbox html code.
	 */
	public function checkbox()
	{
		$keyName = $this->config->get('field.pk');

		return WidgetHelper::render('phoenix.grid.table.checkbox', array(
			'keyName' => $keyName,
			'item'   => $this->current,
			'row'    => $this->row
		), WidgetHelper::EDGE);
	}

	/**
	 * Make a link to direct to foreign table item.
	 *
	 * @param   string $title   Title of link, default is an icon.
	 * @param   string $url     URL to link.
	 * @param   array  $attribs Link element attributes.
	 * @param   array  $options
	 *
	 * @return string Link element.
	 */
	public function foreignLink($title = null, $url = null, array $attribs = array(), array $options = array())
	{
		$defaultAttribs['href']   = $url;
		$defaultAttribs['class']  = 'text-muted muted';
		$defaultAttribs['target'] = '_blank';

		$options['icon'] = isset($options['icon']) ? $options['icon'] : 'icon-out-2 glyphicon glyphicon-share fa fa-external-link';

		$title = $title . ' <small class="' . $options['icon'] . '"></small>';

		return new HtmlElement('a', $title, array_merge($defaultAttribs, $attribs));
	}

	/**
	 * Created date.
	 *
	 * @param string $format The date format.
	 * @param bool   $local  Use local timezone.
	 *
	 * @return string Date string.
	 */
	public function createdDate($format = '', $local = false)
	{
		$field = $this->config->get('field.created', 'created');
		$format = $format ? : DateTime::$format;

		if ($local)
		{
			return DateTime::toLocalTime($this->current->$field, $format);
		}

		return DateTime::create($this->current->$field)->format($format);
	}

	/**
	 * createIconButton
	 *
	 * @param array $options
	 *
	 * @return  IconButton
	 */
	public function createIconButton(array $options = array())
	{
		return IconButton::create($options);
	}

	/**
	 * published
	 *
	 * @param string $value
	 * @param array  $options
	 *
	 * @return  static
	 */
	public function published($value, array $options = array())
	{
		return StateButton::create($options)->render($value, $this->row);
	}

	/**
	 * publishButton
	 *
	 * @param mixed $value
	 * @param array $options
	 *
	 * @return  string
	 */
	public function state($value, array $options = array())
	{
		return StateButton::create($options)->render($value, $this->row);
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string $output The output to escape.
	 *
	 * @return  string  The escaped output.
	 */
	public function escape($output)
	{
		// Escape the output.
		return htmlentities($output, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Method to get property Row
	 *
	 * @return  int
	 */
	public function getRow()
	{
		return $this->row;
	}

	/**
	 * Method to set property row
	 *
	 * @param   int $row
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setRow($row)
	{
		$this->row = $row;

		return $this;
	}

	/**
	 * Method to get property State
	 *
	 * @return  Structure
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * Method to set property state
	 *
	 * @param   Structure $state
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setState(Structure $state)
	{
		$this->state = $state;

		return $this;
	}

	/**
	 * Method to get property Config
	 *
	 * @return  Structure
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Method to set property config
	 *
	 * @param   Structure $config
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setConfig(Structure $config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Method to get property View
	 *
	 * @return  HtmlView
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * Method to set property view
	 *
	 * @param   HtmlView $view
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setView($view)
	{
		$this->view = $view;

		return $this;
	}

	/**
	 * __get
	 *
	 * @param   string $name
	 *
	 * @return  mixed
	 *
	 * @throws \OutOfRangeException
	 */
	public function __get($name)
	{
		$allowFields = array(
			'view',
			'config',
			'state',
			'current',
			'row'
		);

		if (in_array($name, $allowFields))
		{
			return $this->$name;
		}

		if ($name === 'item')
		{
			return $this->current;
		}

		throw new \OutOfRangeException('Property ' . $name . ' not exists.');
	}
}
