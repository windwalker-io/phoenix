<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Utilities;

use Windwalker\Ioc;
use Windwalker\Utilities\Queue\Priority;
use Windwalker\Utilities\Queue\PriorityQueue;

/**
 * The AbstractFactory class.
 *
 * @since  1.0
 */
abstract class AbstractPackageObjectResolver
{
	/**
	 * Property namespaces.
	 *
	 * @var  PriorityQueue[]
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

		foreach (clone static::getNamespaces() as $namespace)
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
	 * @param   int     $priority
	 *
	 * @return  void
	 */
	public static function addNamespace($namespace, $priority = Priority::NORMAL)
	{
		static::getNamespaces()->insert($namespace, $priority);
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
		throw new \LogicException('No longer support remove namespace now. Just reset it.');
	}

	/**
	 * Method to get property Namespaces
	 *
	 * @return  PriorityQueue
	 */
	public static function getNamespaces()
	{
		$called = get_called_class();

		if (!isset(static::$namespaces[$called]))
		{
			static::$namespaces[$called] = new PriorityQueue;
		}

		return static::$namespaces[$called];
	}

	/**
	 * Method to set property namespaces
	 *
	 * @param   array|PriorityQueue  $namespaces
	 *
	 * @return  void
	 */
	public static function setNamespaces($namespaces)
	{
		$called = get_called_class();

		if (!$namespaces instanceof PriorityQueue)
		{
			$namespaces = new PriorityQueue;
		}

		static::$namespaces[$called] = $namespaces;
	}

	/**
	 * reset
	 *
	 * @return  void
	 */
	public static function reset()
	{
		static::setNamespaces(new PriorityQueue);
	}
}
