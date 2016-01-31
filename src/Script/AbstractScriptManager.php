<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Asset\Asset;
use Phoenix\Asset\AssetManager;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Ioc;

/**
 * The ScriptManager class.
 *
 * @since  1.0.13
 */
abstract class AbstractScriptManager
{
	/**
	 * Property inited.
	 *
	 * @var  array
	 */
	protected static $inited = array();

	/**
	 * Property asset.
	 *
	 * @var  AssetManager
	 */
	protected static $asset;

	/**
	 * inited
	 *
	 * @param   string $name
	 * @param   mixed  $data
	 *
	 * @return bool
	 */
	protected static function inited($name, $data = null)
	{
		$id = static::getInitedId($data);

		$class = get_called_class();

		if (!isset(static::$inited[$class][$name][$id]))
		{
			static::$inited[$class][$name][$id] = true;

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
	public static function getInitedId($data)
	{
		return sha1(serialize($data));
	}

	/**
	 * Method to get property Asset
	 *
	 * @return  AssetManager
	 */
	public static function getAsset()
	{
		if (!static::$asset)
		{
			static::$asset = Asset::getInstance();
		}

		return static::$asset;
	}

	/**
	 * Method to set property asset
	 *
	 * @param   AssetManager $asset
	 *
	 * @return  void
	 */
	public static function setAsset($asset)
	{
		static::$asset = $asset;
	}

	/**
	 * phoenixName
	 *
	 * @return  string
	 */
	protected static function phoenixName()
	{
		static $name;

		if ($name)
		{
			return $name;
		}

		return $name = PackageHelper::getAlias('Phoenix\PhoenixPackage');
	}

	/**
	 * getContainer
	 *
	 * @return  \Windwalker\DI\Container
	 */
	public static function getContainer()
	{
		return Ioc::factory();
	}
}
