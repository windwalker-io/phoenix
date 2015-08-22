<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\FileOperator;

use Windwalker\Filesystem\File;
use Windwalker\String\StringHelper;

/**
 * The CopyOperator class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class CopyOperator extends \Muse\FileOperator\CopyOperator
{
	/**
	 * copyFile
	 *
	 * @param string $src
	 * @param string $dest
	 * @param array  $replace
	 *
	 * @return  void
	 */
	protected function copyFile($src, $dest, $replace = array())
	{
		// Replace dest file name.
		$dest = StringHelper::parseVariable($dest, $replace);

		if (is_file($dest))
		{
			$this->io->out('File exists: ' . $dest);
		}
		else
		{
			$content = StringHelper::parseVariable(file_get_contents($src), $replace);
			if (File::write($dest, $content))
			{
				$this->io->out('File created: ' . $dest);
			}
		}
	}
}
