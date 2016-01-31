<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\FileOperator\CopyOperator;
use Windwalker\Filesystem\File;
use Windwalker\Filesystem\Folder;

/**
 * The RenameMigrationAction class.
 *
 * @since  1.0
 */
class RenameMigrationAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$dest = $this->config['dir.dest'];
		$migName = $this->config['replace.controller.item.name.cap'] . 'Init';

		if (!is_dir($dest . '/Migration'))
		{
			return;
		}

		// Copy migration
		$files = Folder::files($dest . '/Migration');

		$file = false;

		// If last migration with same name exists, delete duplicated migration.
		foreach ($files as $file)
		{
			$fileName = pathinfo($file, PATHINFO_BASENAME);

			if (strpos($file, $migName . '.php') !== false && $fileName != '19700101000000_' . $migName . '.php')
			{
				if (is_file($dest . '/Migration/' . '19700101000000_' . $migName . '.php'))
				{
					File::delete($dest . '/Migration/' . '19700101000000_' . $migName . '.php');
				}

				return;
			}
		}

		foreach ($files as $file)
		{
			if (strpos($file, $migName . '.php') !== false)
			{
				break;
			}
		}

		// Migration not exists, return.
		if (!$file)
		{
			return;
		}

		$newName = gmdate('YmdHis') . '_' . $migName . '.php';

		File::move($file, $dest . '/Migration/' . $newName);

		$this->io->out('[<info>Action</info>] Rename migration file to: ' . $newName);
	}
}
