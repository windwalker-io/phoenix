<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Phoenix\Asset;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use Windwalker\Core\Console\CoreCommand;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Filesystem\File;
use Windwalker\String\StringHelper;

/**
 * The MinifyCommand class.
 *
 * @since  1.0
 */
class MinifyCommand extends CoreCommand
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
	 * init
	 *
	 * @return  void
	 */
	public function init()
	{
		$this->addGlobalOption('p')
			->alias('package')
			->description('Package name to minify assets of this package');

		$this->addGlobalOption('d')
			->alias('dir')
			->description('Directory to minify.');
	}

	/**
	 * doExecute
	 *
	 * @return  int
	 */
	protected function doExecute()
	{
		$path = $this->getArgument(0);

		$package = $this->getOption('p');

		$folder = $this->console->get('asset.folder', 'asset');

		if ($package = PackageHelper::getPackage($package))
		{
			$path = $package->getDir() . '/Resources/asset/' . $path;
		}
		else
		{
			$path = WINDWALKER_PUBLIC . '/' . trim($folder, '/') . '/' . $path;
		}

		if (is_file($path))
		{
			$files = [new \SplFileInfo($path)];
		}
		elseif (is_dir($path))
		{
			$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::FOLLOW_SYMLINKS));
		}
		else
		{
			throw new \InvalidArgumentException('No path');
		}

		/** @var \SplFileInfo $file */
		foreach ($files as $file)
		{
			$ext = File::getExtension($file->getPathname());

			if (StringHelper::endsWith($file->getBasename(), '.min.' . $ext))
			{
				continue;
			}

			if ($ext === 'css')
			{
				$this->out('[<comment>Compressing</comment>] ' . $file);

				$data = $this->minifyCSS($file);
			}
			elseif ($ext === 'js')
			{
				$this->out('[<comment>Compressing</comment>] ' . $file);

				$data = $this->minifyJS($file);
			}
			else
			{
				continue;
			}

			$newName = $file->getPath() . '/' . File::stripExtension($file->getBasename()) . '.min.' . $ext;

			file_put_contents($newName, $data);

			$this->out('[<info>Compressed</info>] ' . $newName);
		}

		return true;
	}

	/**
	 * minifyCSS
	 *
	 * @param string $file
	 *
	 * @return  string
	 */
	protected function minifyCSS($file)
	{
		$minify = new CSS;

		$minify->add($file);

		return str_replace("\n", ' ', $minify->minify());
	}

	/**
	 * minifyJS
	 *
	 * @param string $file
	 *
	 * @return  string
	 */
	protected function minifyJS($file)
	{
		$minify = new JS;

		$minify->add($file);

		return str_replace("\n", ' ', $minify->minify());
	}
}
