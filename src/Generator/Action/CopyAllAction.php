<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action;

use Phoenix\Generator\FileOperator\CopyOperator;

/**
 * The CopyAllAction class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class CopyAllAction extends AbstractAction
{
	/**
	 * doExecute
	 *
	 * @throws \RuntimeException
	 * @return  mixed
	 */
	public function doExecute()
	{
		/** @var CopyOperator $copyOperator */
		$copyOperator = $this->container->get('operator.factory')->getOperator('copy');

		$config = $this->config;

		if (!is_dir($config['dir.tmpl']))
		{
			throw new \RuntimeException(sprintf('Template "%s" of %s not exists', $config['template'], $config['type']));
		}

		$copyOperator->copy($config['dir.src'], $config['dir.dest'], $this->replace);
	}
}
