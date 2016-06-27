<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Phoenix;

use Phoenix\Command\Phoenix\Record\SyncCommand;
use Windwalker\Core\Console\CoreCommand;

/**
 * The RecordCommand class.
 *
 * @since  {DEPLOY_VERSION}
 */
class RecordCommand extends CoreCommand
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'record';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Record management';

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		$this->addCommand(new SyncCommand);
	}
}
