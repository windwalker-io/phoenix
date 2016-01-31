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
 * The ListCommand class.
 *
 * @since  1.0
 */
class ListCommand extends Command
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
	protected $name = 'add-list';

	/**
	 * The command description.
	 *
	 * @var  string
	 */
	protected $description = 'Add a plural MVC template.';

	/**
	 * The usage to tell user how to use this command.
	 *
	 * @var string
	 */
	protected $usage = 'list <cmd><package_name></cmd> <option>[option]</option>';

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

		$generator->setTask('package.add.list')->execute();
	}
}
