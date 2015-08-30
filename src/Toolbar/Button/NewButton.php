<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Toolbar\Button;

/**
 * The NewButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class NewButton extends AbstractButton
{
	/**
	 * Property type.
	 *
	 * @var  string
	 */
	protected $type = 'new';

	/**
	 * Constructor
	 *
	 * @param mixed  $content Element content.
	 * @param string $icon    The button icon.
	 * @param array  $attribs Element attributes.
	 */
	public function __construct($content = 'phoenix.toolbar.button.new', $icon = 'plus', $attribs = array())
	{
		parent::__construct($content, null, $attribs);
	}
}
