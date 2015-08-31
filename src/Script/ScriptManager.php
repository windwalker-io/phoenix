<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Script\Module\Module;
use Phoenix\Script\Module\ModuleManager;
use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The ScriptManager class.
 *
 * @see  ModuleManager
 *
 * @method  static  Module         getModule($name)
 * @method  static  ModuleManager  addModule($name, $module)
 * @method  static  boolean        load($name)
 * @method  static  boolean        getModules()
 * @method  static  ModuleManager  setModules($modules)
 * @method  static  boolean        getLegacy()
 * @method  static  ModuleManager  setLegacy($legacy)
 *
 * @since  {DEPLOY_VERSION}
 */
class ScriptManager extends AbstractProxyFacade
{
	/**
	 * Property instance.
	 *
	 * @var  ModuleManager[]
	 */
	protected static $instances;

	/**
	 * getInstance
	 *
	 * @param bool $forceNew
	 *
	 * @return mixed
	 */
	public static function getInstance($forceNew = false)
	{
		$class = get_called_class();

		if (empty(static::$instances[$class]))
		{
			static::$instances[$class] = new ModuleManager;

			static::registerModules(static::$instances[$class]);
		}

		return static::$instances[$class];
	}

	/**
	 * registerCoreModules
	 *
	 * @param ModuleManager $moduleManager
	 *
	 * @return void
	 */
	protected function registerModules(ModuleManager $moduleManager)
	{
	}
}
