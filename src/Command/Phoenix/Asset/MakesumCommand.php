<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix\Asset;

use Windwalker\Console\Command\Command;
use Windwalker\Filesystem\Folder;

/**
 * The SyncCommand class.
 * 
 * @since  1.0
 */
class MakesumCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'makesum';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Make media sum files';

	/**
	 * init
	 *
	 * @return  void
	 */
	public function init()
	{
	}

	/**
	 * doExecute
	 *
	 * @return  int
	 */
	protected function doExecute()
	{
		Folder::create(WINDWALKER_CACHE . '/phoenix');

		$sum = md5(uniqid());

		file_put_contents(WINDWALKER_CACHE . '/phoenix/asset/MD5SUM', $sum);

		$this->out('Create SUM: <info>' . $sum . '</info> at <info>' . WINDWALKER_CACHE . '/phoenix/MD5SUM</info>');

		return true;
	}
}
