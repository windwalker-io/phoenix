<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Windwalker\Console\IO\IOInterface;
use Windwalker\Core\Console\WindwalkerConsole;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Utilities\Classes\MvcHelper;
use Windwalker\Ioc;
use Windwalker\Utilities\Reflection\ReflectionHelper;

/**
 * The SeedAction class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SeedAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$this->io->out('[<comment>SQL</comment>] Importing seeder');

		$package = 'gen_' . $this->config['replace.package.name.lower'];

		if ($package = PackageHelper::getPackage($package))
		{
			$packageClass = get_class($package);
		}
		else
		{
			$packageClass = sprintf(
				'%s%s\%sPackage',
				$this->config['replace.package.namespace'],
				$this->config['replace.package.name.cap'],
				$this->config['replace.package.name.cap']
			);

			PackageHelper::getInstance()->addPackage($package, $packageClass);
		}

		$seedClass = MvcHelper::getPackageNamespace($packageClass, 1) . '\Seed\\' . $this->config['replace.controller.item.name.cap'] . 'Seeder';

		/** @var WindwalkerConsole $app */
		$app = Ioc::getApplication();

		// A dirty work to call migration command.
		/** @var IOInterface $io */
		$io = clone $this->io->getIO();
		$io->setArguments(array('seed', 'import'));
		$io->setOption('c', $seedClass);
		$io->setOption('no-backup', true);

		$app->getRootCommand()->setIO($io)->execute();
	}
}
