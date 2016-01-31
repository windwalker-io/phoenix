<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Symlink;

use Windwalker\Environment\ServerHelper;

/**
 * The Symlink class.
 * 
 * @since  1.0
 */
class Symlink
{
	/**
	 * make
	 *
	 * @param string $src
	 * @param string $dest
	 *
	 * @return  string
	 */
	public static function make($src, $dest)
	{
		$windows = ServerHelper::isWindows();

		$src  = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $src);
		$dest = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $dest);

		if ($windows)
		{
			return exec("mklink /D {$dest} {$src}");
		}
		else
		{
			return exec("ln -s {$src} {$dest}");
		}
	}
}
