<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Controller;

use Muse\Controller\AbstractMuseController;
use Muse\Controller\AbstractTaskController;
use Muse\IO\IOInterface;
use Phoenix\Generator\Provider\MuseProvider;
use Windwalker\Console\Command\Command;
use Windwalker\DI\Container;
use Windwalker\Ioc;
use Windwalker\Structure\Structure;
use Windwalker\String\StringNormalise;

/**
 * The GeneratorController class.
 * 
 * @since  1.0
 */
class GeneratorController extends AbstractMuseController
{
	/**
	 * Property task.
	 *
	 * @var  string
	 */
	protected $task = null;

	/**
	 * Property type.
	 *
	 * @var  string
	 */
	protected $type = null;

	/**
	 * Property element.
	 *
	 * @var  string
	 */
	protected $name = null;

	/**
	 * Property template.
	 *
	 * @var  string
	 */
	protected $template = null;

	/**
	 * Property tagVariables.
	 *
	 * @var  array
	 */
	protected $tagVariables = ['{$', '$}'];

	/**
	 * Property command.
	 *
	 * @var  Command
	 */
	protected $command;

	/**
	 * constructor.
	 *
	 * @param Command     $command
	 * @param Container   $container
	 * @param IOInterface $io
	 */
	public function __construct(Command $command, Container $container = null, IOInterface $io = null)
	{
		$this->command = $command;

		$container = $container ? : Ioc::factory();

		$this->container = $container;

		$container->registerServiceProvider(new MuseProvider($this));

		$io = $io ? : $container->get('io');

		$io->setCommand($command);

		parent::__construct($io);
	}

	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @since   12.1
	 * @throws  \LogicException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		$config = [];

		$this->out()->out('Start generating...')->out();

		// Prepare basic data.
		$command = $this->command;
		$name = $command->getArgument(0);

		list($type, $task) = explode('.', $this->getTask(), 2);

		$this->name     = $config['name']     = $name;
		$this->type     = $config['type']     = $type;
		$this->template = $config['template'] = $this->command->getOption('t');
		$this->template = $config['table']    = $this->command->getOption('table');

		$config['tagVariables'] = (array) $this->tagVariables;
		$config['migrate'] = $this->command->getOption('migrate');
		$config['seed']    = $this->command->getOption('seed');

		// Get Handler
		$task = StringNormalise::toClassNamespace(str_replace('.', '\\', $task));

		$class = sprintf(__NAMESPACE__ . '\%s\%sController', ucfirst($this->type), $task);

		if (!class_exists($class))
		{
			throw new \RuntimeException(sprintf('Task %s of type "%s" not support.', $this->getTask(), $this->type));
		}

		/** @var AbstractTaskController $controller */
		$controller = new $class($this->container, $this->io, new Structure($config));

		$controller->execute();

		$this->out()->out('Template generated.');
	}

	/**
	 * getTask
	 *
	 * @return  string
	 */
	public function getTask()
	{
		return $this->task;
	}

	/**
	 * Set task, format is: {type}.{task} or {type}.{task}.{subTask}
	 *
	 * @param   string $task
	 *
	 * @return  GeneratorController  Return self to support chaining.
	 */
	public function setTask($task)
	{
		if (explode('.', $task) < 2)
		{
			throw new \InvalidArgumentException('Task name should be {type}.{task}');
		}

		$this->task = $task;

		return $this;
	}

	/**
	 * Method to get property TagVariables
	 *
	 * @return  array
	 */
	public function getTagVariables()
	{
		return $this->tagVariables;
	}

	/**
	 * Method to set property tagVariables
	 *
	 * @param   array $tagVariables
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTagVariables($tagVariables)
	{
		$this->tagVariables = $tagVariables;

		return $this;
	}

	/**
	 * Method to get property Command
	 *
	 * @return  Command
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * Method to set property command
	 *
	 * @param   Command $command
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setCommand($command)
	{
		$this->command = $command;

		return $this;
	}
}
