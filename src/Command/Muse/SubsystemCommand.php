<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Muse;

use Phoenix\Generator\Controller\GeneratorController;
use Windwalker\Console\Command\Command;

/**
 * The SubsystemCommand class.
 *
 * @since  1.0
 */
class SubsystemCommand extends Command
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
	protected $name = 'add-subsystem';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Add a singular & plural MVC group.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'list <cmd><package_name></cmd> <option>[option]</option>';

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
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

		$generator->setTask('package.add.subsystem')->execute();
	}
}
