<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html\State;

use Windwalker\Core\Widget\WidgetHelper;

/**
 * The StateButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class IconButton
{
	/**
	 * Property row.
	 *
	 * @var  int
	 */
	protected $row = 0;

	/**
	 * Property value.
	 *
	 * @var  mixed
	 */
	protected $value;

	/**
	 * Property states.
	 *
	 * @var  array
	 */
	protected $states = array(
		'_default' => array(
			'value'     => '_default',
			'task'      => '',
			'icon'      => 'question-sign fa fa-question-circle',
			'title'     => 'Unknown state',
			'only_icon' => false,
			'disabled'  => null,
			'options'   => array()
		)
	);

	/**
	 * Property options.
	 *
	 * @var  array
	 */
	protected $options = array();

	/**
	 * Property template.
	 *
	 * @var  string
	 */
	protected $template = 'phoenix.grid.table.icon-button-new';

	/**
	 * create
	 *
	 * @param mixed $value
	 * @param int   $row
	 * @param array $options
	 *
	 * @return static
	 */
	public static function create($value, $row, $options = array())
	{
		return new static($value, $row, $options);
	}

	/**
	 * StateButton constructor.
	 *
	 * @param mixed   $value
	 * @param integer $row
	 * @param array   $options
	 */
	public function __construct($value, $row, array $options = array())
	{
		$this->row = $row;
		$this->options = $options;
		$this->value = $value;

		$this->configure();
	}

	/**
	 * configure
	 *
	 * @return  void
	 */
	protected function configure()
	{
		// Implement this method.
	}

	/**
	 * addState
	 *
	 * @param string|integer $value
	 * @param string         $task
	 * @param string         $icon
	 * @param null           $title
	 * @param bool           $onlyIcon
	 * @param array          $options
	 *
	 * @return static
	 */
	public function addState($value, $task, $icon = 'ok', $title = null, $onlyIcon = null, $options = array())
	{
		// Force type to prevent null data
		$this->states[$value] = array(
			'value'   => (string) $value,
			'task'    => (string) $task,
			'icon'    => (string) $icon,
			'title'   => (string) $title,
			'only_icon' => (bool) $onlyIcon,
			'options' => $options
		);

		return $this;
	}

	/**
	 * getState
	 *
	 * @param   string|integer $value
	 *
	 * @return  array
	 */
	public function getState($value)
	{
		return isset($this->states[$value]) ? $this->states[$value] : null;
	}

	/**
	 * removeState
	 *
	 * @param   string|integer  $value
	 *
	 * @return  static
	 */
	public function removeState($value)
	{
		if (isset($this->states[$value]))
		{
			unset($this->states[$value]);
		}

		return $this;
	}

	/**
	 * render
	 *
	 * @return  string
	 */
	public function render()
	{
		$data = $this->getState($this->value);

		$data = $data ? : $this->getState('_default');

		$data = array_merge($data, $this->options);
		$data['row'] = (int) $this->row;

		return WidgetHelper::render($this->template, $data, WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * __toString
	 *
	 * @return  string
	 */
	public function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (\Exception $e)
		{
			return (string) $e;
		}
	}

	/**
	 * Method to get property Template
	 *
	 * @return  string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Method to set property template
	 *
	 * @param   string $template
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTemplate($template)
	{
		$this->template = $template;

		return $this;
	}

	/**
	 * Method to get property Value
	 *
	 * @return  mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * Method to set property value
	 *
	 * @param   mixed $value
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setValue($value)
	{
		$this->value = $value;

		return $this;
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
	 * Method to get property Options
	 *
	 * @return  array
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * Method to set property options
	 *
	 * @param   array $options
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;

		return $this;
	}
}
