<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
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
 * @since  {DEPLOY_VERSION}
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
		$package = $this->config['replace.package.name.lower'];
		$packageClass = sprintf(
			'%s%s\%sPackage',
			$this->config['replace.package.namespace'],
			$this->config['replace.package.name.cap'],
			$this->config['replace.package.name.cap']
		);

		PackageHelper::getInstance()->addPackage('gen_' . $package, $packageClass);

		/** @var WindwalkerConsole $app */
		$app = Ioc::getApplication();

		// A dirty work to call migration command.
		/** @var IOInterface $io */
		$io = clone $this->io->getIO();
		$io->setOption('p', 'gen_' . $package);
		$io->setArguments(array('migration', 'migrate'));

		$app->getRootCommand()->setIO($io)->execute();
	}
}
