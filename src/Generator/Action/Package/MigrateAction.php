<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Windwalker\Console\IO\IOInterface;
use Windwalker\Core\Console\WindwalkerConsole;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Ioc;

/**
 * The MigrateAction class.
 *
 * @since  1.0
 */
class MigrateAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->io->out('[<comment>SQL</comment>] Running migrations');

		$package = 'gen_' . $this->config['replace.package.name.lower'];

		if (!PackageHelper::getPackage($package))
		{
			$packageClass = sprintf(
				'%s%s\%sPackage',
				$this->config['replace.package.namespace'],
				$this->config['replace.package.name.cap'],
				$this->config['replace.package.name.cap']
			);

			PackageHelper::getInstance()->addPackage($package, $packageClass);
		}

		/** @var WindwalkerConsole $app */
		$app = Ioc::getApplication();

		// A dirty work to call migration command.
		/** @var IOInterface $io */
		$io = clone $this->io->getIO();
		$io->setArguments(array('migration', 'migrate'));
		$io->setOption('p', $package);
		$io->setOption('seed', null);
		$io->setOption('s', null);
		$io->setOption('no-backup', true);

		$app->getRootCommand()->setIO($io)->execute();
	}
}
