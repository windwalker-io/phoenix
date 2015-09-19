<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Muse;

use Phoenix\Command\Muse;
use Phoenix\Generator\Controller\GeneratorController;
use Windwalker\Console\Command\Command;

/**
 * The ItemCommand class.
 *
 * @since  1.0
 */
class ItemCommand extends Command
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
	protected $name = 'add-item';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Add a singular MVC template.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'item <cmd><package_name></cmd> <option>[option]</option>';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
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

		$generator->setTask('package.add.item')->execute();
	}
}
