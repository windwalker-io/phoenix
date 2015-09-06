<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\FileOperator;

use Windwalker\Filesystem\File;

/**
 * The ConvertOperator class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ConvertOperator extends CopyOperator
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
		$dest = strtr($dest, $replace);

		$dest .= '.tpl';

		if (is_file($dest))
		{
			$this->io->out('[<comment>File exists</comment>] ' . $dest);
		}
		else
		{
			$content = strtr(file_get_contents($src), $replace);

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
