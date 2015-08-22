<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Muse;

use Phoenix\Generator\Controller\GeneratorController;
use Windwalker\Console\Command\Command;

/**
 * The InitCommand class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ConvertCommand extends Command
{
	/**
	 * An enabled flag.
	 *
	 * @var bool
	 */
	public static $isEnabled = true;
	/**
	 * Console(Argument) name.
	 *
	 * @var  string
	 */
	protected $name = 'convert';
	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Convert a package back to template.';
	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'convert <cmd><package_name></cmd> <option>[option]</option>';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		$generator = new GeneratorController($this);

		$generator->setTask('package.convert')->execute();
	}
}
