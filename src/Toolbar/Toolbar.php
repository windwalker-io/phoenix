<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Toolbar;

use Windwalker\Dom\HtmlElement;
use Windwalker\Dom\HtmlElements;

/**
 * The Toolbar class.
 *
 * @since  {DEPLOY_VERSION}
 */
class Toolbar extends HtmlElements
{
	/**
	 * addButton
	 *
	 * @param   string       $name
	 * @param   HtmlElement  $button
	 *
	 * @return  static
	 */
	public function addButton($name, HtmlElement $button)
	{
		$this->elements[$name] = $button;

		return $this;
	}

	/**
	 * Method to get property Buttons
	 *
	 * @return  array
	 */
	public function getButtons()
	{
		return $this->elements;
	}

	/**
	 * Method to set property buttons
	 *
	 * @param   HtmlElement[] $buttons
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setButtons(array $buttons)
	{
		foreach ($buttons as $name => $button)
		{
			$this->addButton($name, $button);
		}

		return $this;
	}

	/**
	 * Offset to set
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->addButton($offset, $value);
	}
}
