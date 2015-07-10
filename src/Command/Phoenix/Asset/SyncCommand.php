<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix\Asset;

use Phoenix\Symlink\Symlink;
use Windwalker\Console\Command\Command;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Filesystem\Folder;

/**
 * The SyncCommand class.
 * 
 * @since  {DEPLOY_VERSION}
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
		$package = $this->app->getPackage($packageName);

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

		$symlink = new Symlink;

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
			$this->out($symlink->make($dir, $target));
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
