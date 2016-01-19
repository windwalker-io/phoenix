<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix\Asset;

use Phoenix\Symlink\Symlink;
use Windwalker\Console\Command\Command;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Environment\ServerHelper;
use Windwalker\Filesystem\Folder;

/**
 * The SyncCommand class.
 * 
 * @since  1.0
 */
class SyncCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'sync';

	/**
	 * Property usage.
	 *
	 * @var  string
	 */
	protected $usage = '%s <cmd><package></cmd> <cmd><target></cmd> <option>[option]</option>';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Sync asset to main media folder';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	public function initialise()
	{
		$this->addOption('s')
			->alias('symbol')
			->defaultValue(true)
			->description('Use symbol link to link asset folders');

		$this->addOption('hard')
			->defaultValue(false)
			->description('Hard copy assets to media folders');
	}

	/**
	 * doExecute
	 *
	 * @return  int
	 */
	protected function doExecute()
	{
		$hard = $this->getOption('hard');

		// Prepare migration path
		$packageName = $this->getArgument(0);

		/** @var AbstractPackage $package */
		$package = $this->app->getPackage($packageName, 'phoenix');

		if ($package)
		{
			$dir = $package->getDir() . '/Resources/media';
		}
		else
		{
			throw new \InvalidArgumentException('Package ' . $packageName . ' not found.');
		}

		$target = $this->getArgument(1, $packageName);

		$target = $this->app->get('path.public') . '/media/' . $target;

		if (is_link($target))
		{
			throw new \RuntimeException('Link ' . $target . ' already created.');
		}

		if ($hard)
		{
			$this->hardCopy($dir, $target);

			$this->out('Copy folder ' . $dir . ' to ' . $target);
		}
		else
		{
			$this->out(Symlink::make($dir, $target));

			if (!ServerHelper::isWindows())
			{
				$this->out('Link success ' . $dir . ' <====> ' . $target);
			}
		}

		return true;
	}

	/**
	 * hardCopy
	 *
	 * @param string $src
	 * @param string $dest
	 *
	 * @return  void
	 */
	protected function hardCopy($src, $dest)
	{
		Folder::copy($src, $dest);
	}
}
