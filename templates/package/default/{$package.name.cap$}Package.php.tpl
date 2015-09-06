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
use Phoenix\Language\TranslatorHelper;
use Phoenix\Record\RecordResolver;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Event\Dispatcher;
use Windwalker\Form\FieldHelper;
use Windwalker\Form\ValidatorHelper;

define('{$package.name.upper$}_ROOT', __DIR__);

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

		// Language
		TranslatorHelper::loadAll($this, 'ini');
	}

	/**
	 * postExecute
	 *
	 * @param string $result
	 *
	 * @return  string
	 */
	protected function postExecute($result = null)
	{
		if (WINDWALKER_DEBUG)
		{
			if (class_exists('Windwalker\Debugger\Helper\DebuggerHelper'))
			{
				DebuggerHelper::addCustomData('Language Orphans', '<pre>' . TranslatorHelper::getFormattedOrphans() . '</pre>');
			}

			TranslatorHelper::dumpOrphans('ini');
		}

		return $result;
	}

	/**
	 * registerListeners
	 *
	 * @param Dispatcher $dispatcher
	 *
	 * @return  void
	 */
	public function registerListeners(Dispatcher $dispatcher)
	{
		parent::registerListeners($dispatcher);
	}
}
