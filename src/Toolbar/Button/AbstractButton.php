<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Toolbar\Button;

use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Dom\HtmlElement;

/**
 * The AbstractButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AbstractButton extends HtmlElement
{
	/**
	 * Property type.
	 *
	 * @var  string
	 */
	protected $type = '';

	/**
	 * Property icon.
	 *
	 * @var  string
	 */
	protected $icon;

	/**
	 * Constructor
	 *
	 * @param mixed  $content Element content.
	 * @param string $icon    The button icon.
	 * @param array  $attribs Element attributes.
	 */
	public function __construct($content = null, $icon = null, $attribs = array())
	{
		$this->icon = $icon;

		parent::__construct('button', $content, $attribs);

		if (!$this->type)
		{
			throw new \LogicException('Button should have type property.');
		}
		$this->icon = $icon;
	}

	/**
	 * toString
	 *
	 * @param boolean $forcePair
	 *
	 * @return  string
	 */
	public function toString($forcePair = false)
	{
		return WidgetHelper::render('phoenix.toolbar.button.' . $this->getType(), get_object_vars($this), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * Method to get property Type
	 *
	 * @return  string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Method to set property type
	 *
	 * @param   string $type
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * Method to get property Icon
	 *
	 * @return  string
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * Method to set property icon
	 *
	 * @param   string $icon
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setIcon($icon)
	{
		$this->icon = $icon;

		return $this;
	}
}
