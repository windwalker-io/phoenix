<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Controller\Package;

use Muse\Controller\AbstractTaskController;
use Muse\IO\IOInterface;
use Windwalker\DI\Container;
use Windwalker\Filesystem\Path;
use Windwalker\Structure\Structure;
use Windwalker\String\StringInflector;
use Windwalker\String\StringNormalise;

/**
 * The AbstractPackageController class.
 * 
 * @since  1.0
 */
abstract class AbstractPackageController extends AbstractTaskController
{
	/**
	 * Property container.
	 *
	 * @var  Container
	 */
	protected $container;

	/**
	 * Property reserved.
	 *
	 * @var  array
	 */
	protected static $keywords = [
		'__halt_compiler', 'abstract', 'and', 'array', 'as', 'break', 'callable', 'case',
		'catch', 'class', 'clone', 'const', 'continue', 'declare', 'default', 'die', 'do', 'echo', 'else', 'elseif',
		'empty', 'enddeclare', 'endfor', 'endforeach', 'endif', 'endswitch', 'endwhile', 'eval', 'exit', 'extends',
		'final', 'for', 'foreach', 'function', 'global', 'goto', 'if', 'implements', 'include', 'include_once',
		'instanceof', 'insteadof', 'interface', 'isset', 'list', 'namespace', 'new', 'or', 'print', 'private',
		'protected', 'public', 'require', 'require_once', 'return', 'static', 'switch', 'throw', 'trait', 'try',
		'unset', 'use', 'var', 'while', 'xor', 'object', 'resource', 'mixed', 'numeric'
	];

	/**
	 * Constructor.
	 *
	 * @param   \Windwalker\DI\Container $container
	 * @param   \Muse\IO\IOInterface     $io
	 * @param   Structure                $config
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct(Container $container, IOInterface $io, Structure $config = null)
	{
		// Get item & list name
		$ctrl = $config['ctrl'] ? : $io->getArgument(1);

		$ctrl = explode('.', $ctrl);

		$inflector = StringInflector::getInstance();

		if (empty($ctrl[0]))
		{
			$ctrl[0] = 'item';
		}

		if (empty($ctrl[1]))
		{
			$ctrl[1] = $inflector->toPlural($ctrl[0]);
		}

		list($itemName, $listName) = $ctrl;

		// Prepare package name
		$class = explode('\\', str_replace('/', '\\', $config['name']));
		$name = array_pop($class);

		$class = StringNormalise::toClassNamespace(implode('\\', $class));
		$class = $class ? $class . '\\' : null;

		// Check keywords
		if (in_array(strtolower($name), static::$keywords))
		{
			throw new \InvalidArgumentException('Do not use reserved keywords: ' . $name);
		}

		if (in_array(strtolower($listName), static::$keywords))
		{
			throw new \InvalidArgumentException('Do not use reserved keywords: ' . $listName);
		}

		if (in_array(strtolower($itemName), static::$keywords))
		{
			throw new \InvalidArgumentException('Do not use reserved keywords: ' . $itemName);
		}

		$config['package.name'] = $name;
		$config['package.namespace'] = $class;

		$this->replace = new Structure;

		$this->replace['package.namespace'] = $class;

		$this->replace['package.name.lower'] = lcfirst($name);
		$this->replace['package.name.upper'] = strtoupper($name);
		$this->replace['package.name.cap']   = ucfirst($name);
		
		$this->replace['controller.list.name.lower'] = strtolower($listName);
		$this->replace['controller.list.name.upper'] = strtoupper($listName);
		$this->replace['controller.list.name.cap']   = ucfirst($listName);

		$this->replace['controller.item.name.lower'] = strtolower($itemName);
		$this->replace['controller.item.name.upper'] = strtoupper($itemName);
		$this->replace['controller.item.name.cap']   = ucfirst($itemName);

		// Set replace to config.
		$config->mergeTo('replace', $this->replace);

		// Set copy dir.
		$config->set('dir.dest', WINDWALKER_SOURCE . '/' . $this->replace['package.namespace'] . $this->replace['package.name.cap']);

		$config->set('dir.tmpl', PHOENIX_TEMPLATES . '/package/' . $config['template']);

		$config->set('dir.src', $config->get('dir.tmpl'));

		// Replace DS
		$config['dir.dest'] = Path::clean($config['dir.dest']);

		$config['dir.tmpl'] = Path::clean($config['dir.tmpl']);

		$config['dir.src'] = Path::clean($config['dir.src']);

		// Push container
		$this->container = $container;

		parent::__construct($io, $config, $this->replace->toArray());
	}
}
