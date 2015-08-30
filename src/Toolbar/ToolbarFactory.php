<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Toolbar;

/**
 * The ToolbarFactory class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ToolbarFactory
{
	/**
	 * Property toolbars.
	 *
	 * @var  Toolbar[]
	 */
	protected $toolbars = array();

	/**
	 * getToolbar
	 *
	 * @param   string  $name
	 *
	 * @return  Toolbar
	 */
	public function getToolbar($name)
	{
		if (!isset($this->toolbars[$name]))
		{
			$this->toolbars[$name] = new Toolbar;
		}

		return $this->toolbars[$name];
	}

	/**
	 * setToolbar
	 *
	 * @param string  $name
	 * @param Toolbar $toolbar
	 *
	 * @return  static
	 */
	public function setToolbar($name, Toolbar $toolbar)
	{
		$this->toolbars[$name] = $toolbar;

		return $this;
	}
}
