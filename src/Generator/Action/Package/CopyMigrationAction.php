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
use Windwalker\Filesystem\Folder;

/**
 * The RenameMigrationAction class.
 *
 * @since  1.0
 */
class CopyMigrationAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		/** @var CopyOperator $copyOperator */
		$copyOperator = $this->container->get('operator.factory')->getOperator('copy');

		$src = $this->config['dir.src'];
		$dest = $this->config['dir.dest'];
		$migName = $this->config['replace.controller.item.name.cap'] . 'Init';

		if (!is_dir($src . '/Migration'))
		{
			return;
		}

		// Copy migration
		$files = Folder::files($dest . '/Migration');

		$hasSameName = false;

		foreach ($files as $file)
		{
			if (strpos($file, $migName . '.php') !== false)
			{
				$hasSameName = true;

				break;
			}
		}

		// Migration already exists, return.
		if ($hasSameName)
		{
			return;
		}

		$migFile = array_shift(Folder::files($src . '/Migration'));

		$newName = gmdate('YmdHis') . '_' . $migName . '.php';

		$copyOperator->copy($migFile, $dest . '/Migration/' . $newName, $this->replace);

		$this->io->out('[<info>Action</info>] Create migration file: ' . $newName);
	}
}
