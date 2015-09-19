<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\FileOperator\CopyOperator;
use Windwalker\String\StringHelper;

/**
 * The InitAllAction class.
 *
 * @since  {DEPLOY_VERSION}
 */
class InitAllAction extends AbstractAction
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

		$src  = $this->config['dir.src'];
		$dest = $this->config['dir.dest'];
		$item = StringHelper::quote('controller.item.name.cap', $this->config['tagVariables']);

		$files = array(
			'Controller',
			'DataMapper',
			'Field',
			'Form',
			'Helper',
			'Model',
			'Record',
			'Resources',
			'Seed',
			'Table',
			'Templates',
			'View',
			''
		);

		foreach ($files as $file)
		{
			$file = sprintf($file, $item);

			if (!file_exists($src . '/' . $file))
			{
				continue;
			}

			$copyOperator->copy(
				$src . '/' . $file,
				$dest . '/' . $file,
				$this->config['replace']
			);
		}
	}
}
