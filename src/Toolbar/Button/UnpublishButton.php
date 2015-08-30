<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Toolbar\Button;

/**
 * The PublishedButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class UnpublishButton extends BatchUpdateButton
{
	/**
	 * Constructor
	 *
	 * @param mixed  $content Element content.
	 * @param array  $attribs Element attributes.
	 */
	public function __construct($content = 'phoenix.toolbar.button.unpublish', $attribs = array())
	{
		parent::__construct($content, null, $attribs);
	}
}
