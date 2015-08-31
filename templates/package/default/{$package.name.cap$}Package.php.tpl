<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\Asset\Asset;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Package\AbstractPackage;

/**
 * The {$package.name.cap$}Package class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$package.name.cap$}Package extends AbstractPackage
{
	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		BootstrapScript::css();
		Asset::addStyle('phoenix/css/phoenix.css');
		Asset::addStyle('{$package.name.lower$}/css/{$package.name.lower$}.css');
	}
}
