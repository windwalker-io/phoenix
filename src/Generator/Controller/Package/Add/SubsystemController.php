<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Controller\Package\Add;

use Phoenix\Generator\Action\Package\AddSeederAction;
use Phoenix\Generator\Action\Package\MigrateAction;
use Phoenix\Generator\Action\Package\AddTableNameAction;
use Phoenix\Generator\Action\Package\CopyMigrationAction;
use Phoenix\Generator\Action\Package\SeedAction;
use Phoenix\Generator\Action\Package\Subsystem;
use Phoenix\Generator\Controller\Package\AbstractPackageController;
use Windwalker\String\StringHelper;

/**
 * The SubsystemController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class SubsystemController extends AbstractPackageController
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
		$this->config['item_name'] = StringHelper::quote('controller.item.name.cap', $this->config['tagVariables']);
		$this->config['list_name'] = StringHelper::quote('controller.list.name.cap', $this->config['tagVariables']);

		$this->doAction(new Subsystem\PrepareAction);
		$this->doAction(new Subsystem\CopyItemAction);
		$this->doAction(new Subsystem\CopyListAction);

		// Some dirty things handling
		$this->doAction(new AddTableNameAction);
		$this->doAction(new CopyMigrationAction);
		$this->doAction(new AddSeederAction);

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
