<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script\Module;

use Phoenix\Asset\Asset;
use Phoenix\Asset\AssetManager;
use Phoenix\PhoenixPackage;
use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Ioc;

/**
 * The ModuleManager class.
 *
 * @since  1.0
 */
class ModuleManager
{
	/**
	 * Property asset.
	 *
	 * @var  AssetManager
	 */
	protected $asset;

	/**
	 * Modules handler storage.
	 *
	 * @var  Module[]
	 */
	protected $modules = array();

	/**
	 * Property inited.
	 *
	 * @var  array
	 */
	protected $inited = array();

	/**
	 * ModuleManager constructor.
	 *
	 * @param AssetManager $asset
	 */
	public function __construct(AssetManager $asset = null)
	{
		$this->asset = $asset ? : Asset::getInstance();
	}

	/**
	 * inited
	 *
	 * @param   mixed $data
	 *
	 * @return  boolean
	 */
	public function inited($name, $data = null)
	{
		$id = $this->getInitedId($data);

		if (!isset($this->inited[$name][$id]))
		{
			$this->inited[$name][$id] = true;

			return false;
		}

		return true;
	}

	/**
	 * getInitedId
	 *
	 * @param   mixed  $data
	 *
	 * @return  string
	 */
	public function getInitedId($data)
	{
		return sha1(serialize($data));
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

		$module->execute($this->getAsset(), $arguments);

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
	 * Method to get property Asset
	 *
	 * @return  AssetManager
	 */
	public function getAsset()
	{
		if (!$this->asset)
		{
			$this->asset = Asset::getInstance();
		}

		return $this->asset;
	}

	/**
	 * Method to set property asset
	 *
	 * @param   AssetManager $asset
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setAsset($asset)
	{
		$this->asset = $asset;

		return $this;
	}

	/**
	 * phoenixName
	 *
	 * @return  string
	 */
	public function phoenixName()
	{
		static $name;

		if ($name)
		{
			return $name;
		}

		$packages = PackageHelper::getPackages();

		foreach ($packages as $package)
		{
			if ($package instanceof PhoenixPackage)
			{
				$name = $package->getName();

				break;
			}
		}

		return $name = 'phoenix';
	}
}
