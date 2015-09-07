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
use Symfony\Component\Yaml\Yaml;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Event\Dispatcher;
use Windwalker\Filesystem\File;
use Windwalker\Filesystem\Folder;
use Windwalker\Form\FieldHelper;
use Windwalker\Form\ValidatorHelper;

if (!defined('{$package.name.upper$}_ROOT'))
{
	define('{$package.name.upper$}_ROOT', __DIR__);
}

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
		BootstrapScript::script();
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

			// Un comment this line, Translator will export all orphans to /cache/language
			// TranslatorHelper::dumpOrphans('ini');
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

	/**
	 * loadRouting
	 *
	 * @return  mixed
	 */
	public function loadRouting()
	{
		$files = Folder::files(__DIR__ . '/Resources/routing');
		$routes = array();

		foreach ($files as $file)
		{
			$ext = File::getExtension($file);

			if ($ext != 'yml')
			{
				continue;
			}

			$routes = array_merge($routes, Yaml::parse(file_get_contents($file)));
		}

		return $routes;
	}
}
