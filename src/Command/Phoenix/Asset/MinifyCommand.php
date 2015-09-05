<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Phoenix\Asset;

use Minify_CSS_UriRewriter;
use Windwalker\Console\Command\Command;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Filesystem\File;
use Windwalker\String\StringHelper;

/**
 * The MinifyCommand class.
 *
 * @since  {DEPLOY_VERSION}
 */
class MinifyCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'minify';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Minify resources';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	public function initialise()
	{
		$this->addGlobalOption('p')
			->alias('package')
			->description('Package name to minify assets of this package');

		$this->addGlobalOption('d')
			->alias('dir')
			->description('Directory to minify.');

		$this->addGlobalOption('r')
			->alias('root')
			->description('Document root to minify css.');
	}

	/**
	 * doExecute
	 *
	 * @return  int
	 */
	protected function doExecute()
	{
		$package = $this->getArgument(0);

		$package = PackageHelper::getPackage($package);

		if ($package instanceof AbstractPackage)
		{
			$path = $package->getDir() . '/Resources/media';
		}

		else
		{
			$path = $this->getOption('dir', WINDWALKER_PUBLIC . '/media');
		}

		if (!$path)
		{
			throw new \InvalidArgumentException('No path');
		}

		$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::FOLLOW_SYMLINKS));

		/** @var \SplFileInfo $file */
		foreach ($files as $file)
		{
			$ext = File::getExtension($file->getPathname());

			if (StringHelper::endsWith($file->getBasename(), '.min.' . $ext))
			{
				continue;
			}

			if ($ext == 'css')
			{
				$data = \Minify_CSS_Compressor::process(file_get_contents($file));
			}
			elseif ($ext == 'js')
			{
				$data = \JSMin::minify(file_get_contents($file));
			}
			else
			{
				continue;
			}

			file_put_contents(
				$file->getPath() . '/' . File::getExtension($file->getBasename()) . '.min.' . $ext,
				$data
			);
		}
	}
}
