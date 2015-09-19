<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix;

use Phoenix\Command\Phoenix\Asset\MakesumCommand;
use Phoenix\Command\Phoenix\Asset\MinifyCommand;
use Phoenix\Command\Phoenix\Asset\SyncCommand;
use Windwalker\Console\Command\Command;

/**
 * The AssetCommand class.
 * 
 * @since  1.0
 */
class AssetCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'asset';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Asset management';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addCommand(new SyncCommand);
		$this->addCommand(new MakesumCommand);
		$this->addCommand(new MinifyCommand);
	}
}
