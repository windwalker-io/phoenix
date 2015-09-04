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
		$dest = StringHelper::parseVariable($dest, $replace, $this->tagVariable);

		if (substr($dest, -4) == '.tpl')
		{
			$dest = substr($dest, 0, -4);
		}

		if (is_file($dest))
		{
			$this->io->out('[<comment>File exists</comment>] ' . $dest);
		}
		else
		{
			$content = StringHelper::parseVariable(file_get_contents($src), $replace, $this->tagVariable);
			if (File::write($dest, $content))
			{
				$this->io->out('[<info>File created</info>] ' . $dest);
			}
		}
	}

	/**
	 * Method to get property TagVariable
	 *
	 * @return  array
	 */
	public function getTagVariable()
	{
		return $this->tagVariable;
	}

	/**
	 * Method to set property tagVariable
	 *
	 * @param   array $tagVariable
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTagVariable($tagVariable)
	{
		$this->tagVariable = $tagVariable;

		return $this;
	}
}
