<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View\Helper;

use Phoenix\Html\State\IconButton;
use Phoenix\Html\State\StateButton;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\View\HtmlView;
use Windwalker\Core\View\PhpHtmlView;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\Dom\HtmlElement;
use Windwalker\Ioc;
use Windwalker\Registry\Registry;

/**
 * The GridHelper class.
 *
 * @property-read  HtmlView  $view
 * @property-read  Registry  $config
 * @property-read  Registry  $state
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
	 * @var PhpHtmlView
	 */
	protected $view;

	/**
	 * Config object.
	 *
	 * @var Registry
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
	 * @var Registry
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
	 * @param PhpHtmlView $view   The view object.
	 * @param array       $config The config object.
	 */
	public function __construct(PhpHtmlView $view, $config = array())
	{
		$this->view   = $view;
		$this->config = $config = ($config instanceof Registry) ? $config : new Registry($config);
		$this->state  = $state = $view['state'] ? : new Registry;

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

		$state->set('list.saveorder', ($listOrder == $orderColumn) && strtoupper($listDirn) == 'ASC');
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
		), WidgetHelper::ENGINE_BLADE);
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
		$pkName = $this->config->get('field.pk');
		$orderField = $this->config['field.ordering'];
		$saveOrder = $this->state->get('list.saveorder');

		return WidgetHelper::render('phoenix.grid.table.order-button', array(
			'item'   => $this->current,
			'row'    => $this->row,
			'pkName' => $pkName,
			'orderField'  => $orderField,
			'saveOrder' => $saveOrder
		), WidgetHelper::ENGINE_BLADE);
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
			return WidgetHelper::render('phoenix.grid.table.saveorder-button', array(), WidgetHelper::ENGINE_BLADE);
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

		return WidgetHelper::render('phoenix.grid.table.checkboxes-toggle', array('options' => $options), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * Checkbox input.
	 *
	 * @return  string Checkbox html code.
	 */
	public function checkbox()
	{
		$pkName = $this->config->get('field.pk');

		return WidgetHelper::render('phoenix.grid.table.checkbox', array(
			'pkName' => $pkName,
			'item'   => $this->current,
			'row'    => $this->row
		), WidgetHelper::ENGINE_BLADE);
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
	public function foreignLink($title = null, $url, $attribs = array(), $options = array())
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
		$format  = $format ? : DateTime::$format;

		return DateTime::create($this->current->$field, $local)->format($format, true);
	}

	/**
	 * createIconButton
	 *
	 * @param mixed $value
	 * @param array $options
	 *
	 * @return  IconButton
	 */
	public function createIconButton($value, $options = array())
	{
		return new IconButton($value, $this->row, $options);
	}

	/**
	 * publishButton
	 *
	 * @param mixed $value
	 * @param array $options
	 *
	 * @return  string
	 */
	public function state($value, $options = array())
	{
		return StateButton::create($value, $this->row, $options);
	}

	/**
	 * booleanButton
	 *
	 * @param string $value
	 * @param array  $options
	 *
	 * @return  string
	 */
	public function booleanButton($value, $options = array())
	{
		$iconMapping = array(
			0 => 'remove text-danger',
			1 => 'ok text-success'
		);

		$taskMapping = array(
			0 => 'publish',
			1 => 'unpublish'
		);

		return $this->stateButton($value, $taskMapping, $iconMapping, $options);
	}

	/**
	 * Show a boolean icon.
	 *
	 * @param   mixed  $value       A variable has value or not.
	 * @param   array  $taskMapping Click to call a component task. Not available yet.
	 * @param   array  $iconMapping The state to icon mapping.
	 * @param   array  $options     Some options.
	 *
	 * @return string A boolean icon HTML string.
	 */
	public function stateButton($value, $taskMapping = array(), $iconMapping = array(), $options = array())
	{
		$options['titleMapping'] = isset($options['titleMapping']) ? (array) $options['titleMapping'] : array();
		$options['template'] = isset($options['template']) ? $options['template'] : 'phoenix.grid.table.icon-button';

		return WidgetHelper::render($options['template'], array(
			'value' => (string) $value,
			'taskMapping' => $taskMapping,
			'iconMapping' => $iconMapping,
			'item' => $this->current,
			'row'  => $this->row,
			'options' => $options
		), WidgetHelper::ENGINE_BLADE);
	}

//	/**
//	 * The language title.
//	 *
//	 * @return  string Language title.
//	 */
//	public function language()
//	{
//		$field = $this->config->get('field.language', 'language');
//		$title = $this->config->get('field.lang_title', 'lang_title');
//		$all   = $this->config->get('lang.all', 'phoenix.all');
//
//		if ($this->current->$field == '*')
//		{
//			return Translator::translate($all);
//		}
//		else
//		{
//			return $this->current->$title ? $this->escape($this->current->$title) : '-';
//		}
//	}

//	/**
//	 * The reorder title.
//	 *
//	 * @return string
//	 */
//	public function orderTitle()
//	{
//		$orderColumn = $this->state->get('list.orderColumn', $this->config->get('order_column'));
//
//		return $this->sortTitle('', $orderColumn, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
//	}

//	/**
//	 * Drag sort symbol.
//	 *
//	 * @return string
//	 */
//	public function dragSort()
//	{
//		$iconClass  = '';
//		$input      = '';
//		$item       = $this->current;
//		$orderField = $this->config->get('field.ordering', 'ordering');
//		$canChange  = $this->state->get('access.canChange', true);
//		$saveOrder  = $this->state->get('list.saveorder', false);
//
//		if (!$canChange)
//		{
//			$iconClass = ' inactive';
//		}
//		elseif (!$saveOrder)
//		{
//			$iconClass = ' inactive tip-top hasTooltip" title="' . \JHtml::tooltipText('JORDERINGDISABLED');
//		}
//
//		if ($canChange && $saveOrder)
//		{
//			$input = '<input type="text" style="display:none" name="order[]" size="5" value="' . $item->$orderField . '" class="width-20 text-area-order " />';
//		}
//
//		$html = <<<HTML
//		<span class="sortable-handler{$iconClass}">
//			<i class="icon-menu"></i>
//		</span>
//		{$input}
//		<span class="label">
//			{$item->$orderField}
//		</span>
//HTML;
//
//		return $html;
//	}

//	/**
//	 * Can do what?
//	 *
//	 * @param string $action The action which can do or not.
//	 *
//	 * @return boolean Can do or not.
//	 */
//	public function can($action)
//	{
//		$action = 'can' . ucfirst($action);
//
//		return $this->state->get('access.' . $action, true);
//	}

//	/**
//	 * Method to escape output.
//	 *
//	 * @param   string $output The output to escape.
//	 *
//	 * @return  string  The escaped output.
//	 *
//	 * @see     \JView::escape()
//	 */
//	public function escape($output)
//	{
//		// Escape the output.
//		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
//	}

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
	 * @return  Registry
	 */
	public function getState()
	{
		return $this->state;
	}

	/**
	 * Method to set property state
	 *
	 * @param   Registry $state
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setState(Registry $state)
	{
		$this->state = $state;

		return $this;
	}

	/**
	 * Method to get property Config
	 *
	 * @return  Registry
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Method to set property config
	 *
	 * @param   Registry $config
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setConfig(Registry $config)
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
	 * @param   string  $name
	 *
	 * @return  mixed
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

		if ($name == 'item')
		{
			return $this->current;
		}

		throw new \OutOfRangeException('Property ' . $name . ' not exists.');
	}
}
