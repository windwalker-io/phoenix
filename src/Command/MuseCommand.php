<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command;

use Phoenix\Command\Muse;
use Windwalker\Console\Command\Command;

/**
 * The RikiCommand class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class MuseCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'muse';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'The template generator.';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addCommand(new Muse\InitCommand);
		$this->addCommand(new Muse\SubsystemCommand);
		$this->addCommand(new Muse\ItemCommand);
		$this->addCommand(new Muse\ListCommand);
		$this->addCommand(new Muse\ConvertCommand);

		$this->addGlobalOption('type')
			->description('Generate type.')
			->defaultValue('package');

		$this->addGlobalOption('t')
			->alias('tmpl')
			->defaultValue('default')
			->description('Using template.');
	}
}
