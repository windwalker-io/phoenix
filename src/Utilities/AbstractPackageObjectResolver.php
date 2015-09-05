<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Utilities;

use Windwalker\Ioc;

/**
 * The AbstractFactory class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractPackageObjectResolver
{
	/**
	 * Property namespaces.
	 *
	 * @var  array
	 */
	protected static $namespaces = array();

	/**
	 * Property instances.
	 *
	 * @var  array
	 */
	protected static $instances = array();

	/**
	 * create
	 *
	 * @param   string  $name
	 * @param   array   $args
	 *
	 * @return  object
	 */
	public static function create($name, $args = array())
	{
		$name = static::getClass($name);

		foreach (static::$namespaces as $namespace)
		{
			$class = $namespace . '\\' . $name;

			if (!class_exists($class))
			{
				continue;
			}

			$args = (array) $args;

			return static::createObject($class, $args);
		}

		return null;
	}

	/**
	 * createObject
	 *
	 * @param   string $class
	 * @param   array  $args
	 *
	 * @return object
	 */
	protected static function createObject($class, $args = array())
	{
		throw new \LogicException('Please implement: ' . get_called_class() . '::' . __FUNCTION__ . '()');
	}

	/**
	 * getInstance
	 *
	 * @param string  $name
	 * @param array   $args
	 * @param boolean $forceNew
	 *
	 * @return object
	 */
	public static function getInstance($name, $args = array(), $forceNew = false)
	{
		$key = strtolower($name);

		$called = get_called_class();

		if (empty(static::$instances[$called][$key]) || $forceNew)
		{
			static::$instances[$called][$key] = static::create($name, $args);
		}

		return static::$instances[$called][$key];
	}

	/**
	 * getClass
	 *
	 * @param   string  $name
	 *
	 * @return  string
	 */
	public static function getClass($name)
	{
		return ucfirst($name);
	}

	/**
	 * getContainer
	 *
	 * @param string $name
	 * @param string $profile
	 *
	 * @return  \Windwalker\DI\Container
	 */
	public static function getContainer($name = null, $profile = null)
	{
		return Ioc::factory($name, $profile);
	}

	/**
	 * addNamespace
	 *
	 * @param   string  $namespace
	 *
	 * @return  void
	 */
	public static function addNamespace($namespace)
	{
		static::$namespaces[] = $namespace;
	}

	/**
	 * removeNamespace
	 *
	 * @param   string  $namespace
	 *
	 * @return  void
	 */
	public static function removeNamespace($namespace)
	{
		foreach (static::$namespaces as $k => $ns)
		{
			if (strcasecmp($namespace, $ns) === 0)
			{
				unset(static::$namespaces[$k]);

				break;
			}
		}
	}

	/**
	 * Method to get property Namespaces
	 *
	 * @return  array
	 */
	public static function getNamespaces()
	{
		return static::$namespaces;
	}

	/**
	 * Method to set property namespaces
	 *
	 * @param   array $namespaces
	 *
	 * @return  void
	 */
	public static function setNamespaces($namespaces)
	{
		static::$namespaces = $namespaces;
	}
}
