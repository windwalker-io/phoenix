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
class StateButton extends BooleanButton
{
	/**
	 * configure
	 *
	 * @return  void
	 */
	protected function configure()
	{
		parent::configure();

		$this->addState(-1, 'publish', 'trash fa fa-trash', 'phoenix.grid.state.trashed');
	}
}
