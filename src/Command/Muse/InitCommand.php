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
class InitCommand extends Command
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
	protected $name = 'init';
	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Init a new package.';
	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'init <cmd><package_name></cmd> <option>[option]</option>';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
		$this->addGlobalOption('table')
			->description('The database table name.');

		$this->addGlobalOption('m')
			->alias('migrate')
			->description('Run migration.');

		$this->addGlobalOption('s')
			->alias('seed')
			->description('Run seeder.');
	}

	/**
	 * Execute this command.
	 *
	 * @return int|void
	 */
	protected function doExecute()
	{
		$generator = new GeneratorController($this);

		$generator->setTask('package.init')->execute();
	}
}
