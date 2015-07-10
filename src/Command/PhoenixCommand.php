<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command;

use Phoenix\Command\Phoenix\AssetCommand;
use Phoenix\Command\Phoenix\FormCommand;
use Windwalker\Console\Command\Command;

/**
 * The RikiCommand class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class PhoenixCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'riki';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Riki simple RAD';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addCommand(new AssetCommand);
		$this->addCommand(new FormCommand);
	}
}
