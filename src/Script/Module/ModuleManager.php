<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script\Module;

use Phoenix\Asset\Asset;
use Phoenix\Asset\AssetManager;
use Phoenix\Script\Module\Module;
use Windwalker\Core\Application\WebApplication;
use Windwalker\Ioc;

/**
 * The ModuleManager class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ModuleManager
{
	/**
	 * THe asset helpers storage.
	 *
	 * @var  AssetManager
	 */
	protected $asset = array();

	/**
	 * Modules handler storage.
	 *
	 * @var  Module[]
	 */
	protected $modules = array();

	/**
	 * Property legacy.
	 *
	 * @var  boolean
	 */
	protected $legacy = false;

	/**
	 * Class init.
	 */
	public function __construct()
	{
		$this->registerCoreModules();
	}

	/**
	 * Load RequireJS.
	 *
	 * @return  void
	 */
	public function requireJS()
	{
		$this->load(__FUNCTION__);
	}

	/**
	 * Load underscore.
	 *
	 * @param boolean $noConflict Enable underscore no conflict mode.
	 *
	 * @return  void
	 */
	public function underscore($noConflict = true)
	{
		$this->load(__FUNCTION__, $noConflict);
	}

	/**
	 * Include Backbone. Note this library may not support old IE browser.
	 *
	 * Please see: http://backbonejs.org/
	 *
	 * @param   boolean $noConflict
	 *
	 * @return  void
	 */
	public function backbone($noConflict = false)
	{
		$this->load(__FUNCTION__, $noConflict);
	}

	/**
	 * Load Windwalker script.
	 *
	 * @return  void
	 */
	public function windwalker()
	{
		$this->load(__FUNCTION__);
	}

	/**
	 * Add Module callback.
	 *
	 * @param string   $name
	 * @param callable $handler
	 *
	 * @return  static
	 */
	public function addModule($name, $handler)
	{
		$name = strtolower($name);

		$this->modules[$name] = new Module($name, $handler, $this);

		return $this;
	}

	/**
	 * load
	 *
	 * @param string $name Module name.
	 *
	 * @return  boolean
	 */
	public function load($name)
	{
		$arguments = func_get_args();
		array_shift($arguments);

		$module = $this->getModule($name);

		if (!$module)
		{
			$app = Ioc::getApplication();

			if ($app instanceof WebApplication)
			{
				$app->addFlash(sprintf('Asset module: %s not found.', strtolower($name)));
			}

			return false;
		}

		if ($this->legacy && $module->inited())
		{
			return true;
		}

		$module->execute(Asset::getInstance(), $arguments);

		return true;
	}

	/**
	 * Magic method to call modules.
	 *
	 * @param string $name
	 * @param array  $args
	 *
	 * @return  boolean
	 */
	public function __call($name, $args = array())
	{
		if (strpos($name, 'load') === 0)
		{
			$name = substr($name, 4);
		}

		array_unshift($args, $name);

		return call_user_func_array(array($this, 'load'), $args);
	}

	/**
	 * getModule
	 *
	 * @param string $name
	 *
	 * @return  Module
	 */
	public function getModule($name)
	{
		$name = strtolower($name);

		if (empty($this->modules[$name]))
		{
			return null;
		}

		return $this->modules[$name];
	}

	/**
	 * Method to get property Modules
	 *
	 * @return  Module[]
	 */
	public function getModules()
	{
		return $this->modules;
	}

	/**
	 * Method to set property modules
	 *
	 * @param   Module[] $modules
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setModules($modules)
	{
		$this->modules = $modules;

		return $this;
	}

	/**
	 * Method to get property Legacy
	 *
	 * @return  boolean
	 */
	public function getLegacy()
	{
		return $this->legacy;
	}

	/**
	 * Method to set property legacy
	 *
	 * @param   boolean $legacy
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setLegacy($legacy)
	{
		$this->legacy = $legacy;

		return $this;
	}

	/**
	 * registerCoreModules
	 *
	 * @return  void
	 */
	protected function registerCoreModules()
	{

	}
}
