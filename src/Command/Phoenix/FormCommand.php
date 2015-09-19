<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix;

use Phoenix\Command\Phoenix\Form\GenFieldCommand;
use Windwalker\Console\Command\Command;

/**
 * The FormCommand class.
 * 
 * @since  1.0
 */
class FormCommand extends  Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'form';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Form management';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addCommand(new GenFieldCommand);

		$this->addGlobalOption('d')
			->alias('dir')
			->description('Set migration file directory.');

		$this->addGlobalOption('p')
			->alias('package')
			->description('Package to run migration.');
	}
}
