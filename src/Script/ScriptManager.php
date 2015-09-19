<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Asset\AssetManager;
use Phoenix\Script\Module\Module;
use Phoenix\Script\Module\ModuleManager;
use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The ScriptManager class.
 *
 * @see  ModuleManager
 *
 * @method  static  boolean        inited($name, $data = null)
 * @method  static  string         getInitedId($data)
 * @method  static  AssetManager   getAsset()
 * @method  static  Module         getModule($name)
 * @method  static  ModuleManager  addModule($name, $module)
 * @method  static  boolean        load($name)
 * @method  static  boolean        getModules()
 * @method  static  ModuleManager  setModules($modules)
 * @method  static  string         phoenixName()
 *
 * @since  1.0
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
			static::$instances[$class] = new ModuleManager(static::getContainer()->get('phoenix.asset'));
		}

		return static::$instances[$class];
	}
}
