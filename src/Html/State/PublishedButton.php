<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html\State;

/**
 * The BooleanButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class PublishedButton extends IconButton
{
	/**
	 * configure
	 *
	 * @return  void
	 */
	protected function init()
	{
		$this->addState(0, 'publish',   'remove fa fa-remove text-danger', 'phoenix.grid.state.unpublished')
			->addState(1,  'unpublish', 'ok fa fa-check text-success',     'phoenix.grid.state.published');
	}
}
