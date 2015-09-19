<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Controller\Package;

use Phoenix\Generator\Action;
use Phoenix\Generator\Action\Package\MigrateAction;
use Phoenix\Generator\Action\Package\SeedAction;

/**
 * The InitController class.
 * 
 * @since  1.0
 */
class InitController extends AbstractPackageController
{
	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @throws  \LogicException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		$this->doAction(new Action\CopyAllAction);
		$this->doAction(new Action\Package\RenameMigrationAction);

		if ($this->config['migrate'])
		{
			$this->doAction(new MigrateAction);

			if ($this->config['seed'])
			{
				$this->doAction(new SeedAction);
			}
		}
	}
}
