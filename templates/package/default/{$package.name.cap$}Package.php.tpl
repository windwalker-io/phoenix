<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\Asset\Asset;
use Phoenix\DataMapper\DataMapperResolver;
use Phoenix\Record\RecordResolver;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Form\FieldHelper;
use Windwalker\Form\ValidatorHelper;

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
		// Prepare Resolvers
		RecordResolver::addNamespace(__NAMESPACE__ . '\Record');
		DataMapperResolver::addNamespace(__NAMESPACE__ . '\DataMapper');
		FieldHelper::addNamespace(__NAMESPACE__ . '\Field');
		ValidatorHelper::addNamespace(__NAMESPACE__ . 'Validator');

		// Assets
		BootstrapScript::css();
		Asset::addStyle('{$package.name.lower$}/css/{$package.name.lower$}.css');
	}
}
