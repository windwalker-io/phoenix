<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html\State;

/**
 * The StateButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class StateButton extends IconButton
{
	/**
	 * configure
	 *
	 * @return  void
	 */
	protected function configure()
	{
		$this->addState(-1, 'publish', 'trash fa fa-trash', 'phoenix.grid.state.trashed')
			->addState(0, 'publish', 'remove fa fa-remove text-danger', 'phoenix.grid.state.unpublished')
			->addState(1, 'unpublish', 'ok fa fa-check text-success', 'phoenix.grid.state.published');
	}
}
