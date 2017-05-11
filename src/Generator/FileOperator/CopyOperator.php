<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\FileOperator;

use Windwalker\Filesystem\File;
use Windwalker\String\SimpleTemplate;

/**
 * The CopyOperator class.
 * 
 * @since  1.0
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
	protected function copyFile($src, $dest, $replace = [])
	{
		// Replace dest file name.
		$dest = SimpleTemplate::render($dest, $replace, $this->tagVariable);

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
			$content = SimpleTemplate::render(file_get_contents($src), $replace, $this->tagVariable);
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
