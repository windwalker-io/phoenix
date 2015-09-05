<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\GeneratorHelper;
use Windwalker\String\StringHelper;

/**
 * The AddRoutingAction class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AddRoutingAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$file = $this->config['dir.dest'] . '/routing.yml';
		$list = $this->config['list_name'];

		if (!is_file($file))
		{
			return;
		}

		$code = file_get_contents($file);

		if (strpos($code, $list . ':') !== false || strpos($code, $list . ':') !== false)
		{
			return;
		}

		$routing = $this->config['dir.src'] . '/routing.yml.tpl';

		$replace = file_get_contents($routing);
		$replace = StringHelper::parseVariable($replace, $this->replace, $this->config['tagVariables']);

		$code = GeneratorHelper::addBeforePlaceholder('routing', $code, $replace . "\n", '#');

		file_put_contents($file, $code);

		$this->io->out('[<info>Action</info>] Add new routes to routing.yml');
	}
}
