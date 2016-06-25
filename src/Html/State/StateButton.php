<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html\State;

/**
 * The StateButton class.
 *
 * @since  {DEPLOY_VERSION}
 */
class StateButton extends PublishedButton
{
	/**
	 * configure
	 *
	 * @return  void
	 */
	protected function init()
	{
		parent::init();

		$this->addState(-1, 'publish', 'trash fa fa-trash', 'phoenix.grid.state.trashed');
	}
}
