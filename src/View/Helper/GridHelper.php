<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View\Helper;

use Windwalker\Core\View\HtmlView;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
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
 * @since  {DEPLOY_VERSION}
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
	 * @var Registry
	 */
	protected $config = array();

	/**
	 * The fields mapper.
	 *
	 * @var array
	 */
	protected $fields = array(
		'pk'               => 'id',
		'title'            => 'title',
		'alias'            => 'alias',
		'state'            => 'state',
		'author'           => 'created_by',
		'author_name'      => 'user_name',
		'created'          => 'created',
		'language'         => 'language',
		'lang_title'       => 'lang_title'
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
	 * @param HtmlView $view   The view object.
	 * @param array    $config The config object.
	 */
	public function __construct(HtmlView $view, $config = array())
	{
		$this->view   = $view;
		$this->config = $config = ($config instanceof Registry) ? $config : new Registry($config);
		$this->state  = $state = $view['state'] ? : new Registry;

		// Merge fields
		$fields = $config->get('field');

		$fields = array_merge($this->fields, (array) $fields);

		$this->config->set('field', (object) $fields);

		// Access context
		$this->context = $this->config->get('option') . '.' . $this->config->get('view_item');

		// Ordering
		$listOrder = $state->get('list.ordering');
		$orderCol  = $state->get('list.orderCol', $config->get('order_column'));
		$listDirn  = $this->state->get('list.direction');

		$state->set('list.saveorder', ($listOrder == $orderCol) && strtoupper($listDirn) == 'ASC');
	}

	/**
	 * Register table drg sorting script.
	 *
	 * @param   string   $task    Sorting task for ajax.
	 * @param   integer  $tableId The table id selector.
	 *
	 * @return bool
	 */
	public function registerTableSort($task = null, $tableId = null)
	{
		if (!$this->state->get('list.saveorder', false))
		{
			return false;
		}

		$option   = $this->config->get('option');
		$task     = $task ? : $this->config->get('view_list') . '.state.reorder';
		$listDirn = $this->state->get('list.direction');
		$tableId  = $tableId ? : $this->config->get('order_table_id', 'TableList');

		$saveOrderingUrl = 'index.php?option=' . $option . '&task=' . $task . '&tmpl=component';
		$formName  = $this->config->get('form_name', 'adminForm');

		\JHtml::_('sortablelist.sortable', $tableId, $formName, strtolower($listDirn), $saveOrderingUrl);

		return true;
	}

	/**
	 * Method to sort a column in a grid
	 *
	 * @param   string  $label  The link title
	 * @param   string  $field  The order field for the column
	 * @param   string  $task   An optional task override
	 * @param   string  $newDir An optional direction for the new column
	 * @param   string  $tip    An optional text shown as tooltip title instead of $title
	 * @param   string  $icon   Icon to show
	 *
	 * @return  string
	 */
	public function sortTitle($label, $field, $task = null, $newDir = 'asc', $tip = '', $icon = null)
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
	 * The reorder title.
	 *
	 * @return string
	 */
	public function orderTitle()
	{
		$orderCol = $this->state->get('list.orderCol', $this->config->get('order_column'));

		return $this->sortTitle('', $orderCol, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2');
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
	 * Drag sort symbol.
	 *
	 * @return string
	 */
	public function dragSort()
	{
		$iconClass  = '';
		$input      = '';
		$item       = $this->current;
		$orderField = $this->config->get('field.ordering', 'ordering');
		$canChange  = $this->state->get('access.canChange', true);
		$saveOrder  = $this->state->get('list.saveorder', false);

		if (!$canChange)
		{
			$iconClass = ' inactive';
		}
		elseif (!$saveOrder)
		{
			$iconClass = ' inactive tip-top hasTooltip" title="' . \JHtml::tooltipText('JORDERINGDISABLED');
		}

		if ($canChange && $saveOrder)
		{
			$input = '<input type="text" style="display:none" name="order[]" size="5" value="' . $item->$orderField . '" class="width-20 text-area-order " />';
		}

		$html = <<<HTML
		<span class="sortable-handler{$iconClass}">
			<i class="icon-menu"></i>
		</span>
		{$input}
		<span class="label">
			{$item->$orderField}
		</span>
HTML;

		return $html;
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
	 * Build the edit link.
	 *
	 * @param   string  $title    Title of link, default is an icon.
	 * @param   string  $task     View name to build task: `{view}.edit.edit`.
	 * @param   int     $id       Edit id.
	 * @param   array   $query    URL query array.
	 * @param   array   $attribs  Link element attributes.
	 *
	 * @return string
	 */
	public function editTitle($title = null, $task = null, $id = null, $query = array(), $attribs = array())
	{
		$canEdit    = $this->state->get('access.canEdit', true);
		$canEditOwn = $this->state->get('access.canEditOwn', true);

		$item       = $this->current;
		$pkName     = $this->config->get('field.pk');
		$titleField = $this->config->get('field.title');

		$title = $title ? : $this->escape($item->$titleField);

		$defaultQuery = array(
			'option' => $this->config->get('option'),
			'task'   => $task ? : $this->config->get('view_item') . '.edit.edit',
			$pkName  => $id ? : $this->current->$pkName
		);

		$query = array_merge($defaultQuery, $query);

		$uri = new \JUri;

		$uri->setQuery($query);

		if ($canEdit || $canEditOwn)
		{
			return \JHtml::link($uri, $title, $attribs);
		}
		else
		{
			return $item->$titleField;
		}
	}

	/**
	 * For some list page to quickly build edit link button.
	 *
	 * Usage: `echo $grid->editButton('address', $id);`
	 *
	 * @param   string  $title    Title of link, default is an icon.
	 * @param   string  $task     View name to build task: `{view}.edit.edit`.
	 * @param   int     $id       Edit id.
	 * @param   array   $query    URL query array.
	 * @param   array   $attribs  Link element attributes.
	 *
	 * @return  string  Link element.
	 */
	public function editButton($title = null, $task = null, $id = null, $query = array(), $attribs = array())
	{
		$title = $title ? : new HtmlElement('span', null, array('class' => 'icon-edit icon-white glyphicon glyphicon-edit'));

		$attribs['class'] = !empty($attribs['class']) ? $attribs['class'] : 'btn btn-mini';

		return $this->editTitle($title, $task, $id, $query, $attribs);
	}

	/**
	 * Make a link to direct to foreign table item.
	 * Note that the ordering or id and title are different from `editButton()`, but others are same.
	 *
	 * Usage: `echo $grid->foreignLink('customer', $item->customer_name, $item->customer_id);`
	 *
	 * @param   string  $title    Title of link, default is an icon.
	 * @param   string  $task     View name to build task: `{view}.edit.edit`.
	 * @param   int     $fk       Edit foreign id.
	 * @param   array   $query    URL query array.
	 * @param   array   $attribs  Link element attributes.
	 *
	 * @return  string  Link element.
	 */
	public function foreignLink($title = null, $task = null, $fk = null, $query = array(), $attribs = array())
	{
		if (!$fk)
		{
			return '';
		}

		$title = $title . ' <small class="icon-out-2 glyphicon glyphicon-share"></small>';

		$attribs['class'] = 'text-muted muted';
		$attribs['target'] = '_blank';

		return $this->editTitle($title, $task, $fk, $query, $attribs);
	}

	/**
	 * State button.
	 *
	 * @param string $taskPrefix The task prefix.
	 *
	 * @return string State button html code.
	 */
	public function state($taskPrefix = null)
	{
		$item       = $this->current;
		$canChange  = $this->state->get('access.canChange', true);
		$taskPrefix = $taskPrefix ? : $this->config->get('view_list') . '.state.';
		$field      = $this->config->get('field.state', 'state');

		return \JHtmlJGrid::published($item->$field, $this->row, $taskPrefix, $canChange, 'cb', $item->publish_up, $item->publish_down);
	}

	/**
	 * Check-out button.
	 *
	 * @param string $taskPrefix The task prefix.
	 *
	 * @return string Check-out button html code.
	 */
	public function checkoutButton($taskPrefix = null)
	{
		$item  = $this->current;
		$field = $this->config->get('field.checked_out', 'checked_out');

		$authorNameField = $this->config->get('field.author_name');
		$chkTimeField    = $this->config->get('field.checked_out_time');
		$canCheckin      = $this->state->get('access.canCheckin', true);
		$taskPrefix      = $taskPrefix ? : $this->config->get('view_list') . '.check.';

		if (!$item->$field)
		{
			return '';
		}

		return \JHtmlJGrid::checkedout(
			$this->row,
			$item->$authorNameField,
			$item->$chkTimeField,
			$taskPrefix,
			$canCheckin
		);
	}

	/**
	 * Created date.
	 *
	 * @param string $format The date format.
	 *
	 * @return  string Date string.
	 */
	public function createdDate($format = '')
	{
		$field = $this->config->get('field.created', 'created');
		$format  = $format ? : JText::_('DATE_FORMAT_LC4');

		return JHtml::date($this->current->$field, $format);
	}

	/**
	 * The language title.
	 *
	 * @return  string Language title.
	 */
	public function language()
	{
		$field = $this->config->get('field.language', 'language');
		$title = $this->config->get('field.lang_title', 'lang_title');

		if ($this->current->$field == '*')
		{
			return JText::alt('JALL', 'language');
		}
		else
		{
			return $this->current->$title ? $this->escape($this->current->$title) : JText::_('JUNDEFINED');
		}
	}

	/**
	 * Show a boolean icon.
	 *
	 * @param   mixed  $value   A variable has value or not.
	 * @param   string $task    Click to call a component task. Not available yet.
	 * @param   array  $options Some options.
	 *
	 * @return  string  A boolean icon HTML string.
	 */
	public function booleanIcon($value, $task = '', $options = array())
	{
		$class = $value ? 'icon-publish' : 'icon-unpublish';

		return "<i class=\"{$class}\"></i>";
	}

	/**
	 * Can do what?
	 *
	 * @param string $action The action which can do or not.
	 *
	 * @return boolean Can do or not.
	 */
	public function can($action)
	{
		$action = 'can' . ucfirst($action);

		return $this->state->get('access.' . $action, true);
	}

	/**
	 * Method to escape output.
	 *
	 * @param   string $output The output to escape.
	 *
	 * @return  string  The escaped output.
	 *
	 * @see     \JView::escape()
	 */
	public function escape($output)
	{
		// Escape the output.
		return htmlspecialchars($output, ENT_COMPAT, 'UTF-8');
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
