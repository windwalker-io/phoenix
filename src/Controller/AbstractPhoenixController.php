<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\Data\DataInterface;
use Windwalker\DI\Container;
use Windwalker\Filter\InputFilter;
use Windwalker\IO\Input;
use Windwalker\Router\Exception\RouteNotFoundException;
use Windwalker\String\StringInflector;

/**
 * The AbstractRadController class.
 * 
 * @since  1.0
 */
abstract class AbstractPhoenixController extends AbstractController
{
	const SINGULAR = 'singular';
	const PLURAL   = 'plural';

	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = null;

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName;

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName;

	/**
	 * The default Model.
	 *
	 * If set model name here, controller will get model object by this name.
	 *
	 * @var  ModelRepository
	 */
	protected $model;

	/**
	 * Property prefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'phoenix.';

	/**
	 * Class init.
	 *
	 * @param Input           $input
	 * @param Container       $container
	 * @param AbstractPackage $package
	 */
	public function __construct(Input $input = null, AbstractPackage $package = null, Container $container = null)
	{
		$this->config = $this->getConfig();

		$name = $this->getName();

		// Guess name
		$inflector = StringInflector::getInstance();

		if ($this->inflection == self::SINGULAR)
		{
			$this->config['item_name'] = $this->itemName ? : $name;
			$this->config['list_name'] = $this->listName ? : $inflector->toPlural($this->config['item_name']);
		}
		elseif ($this->inflection == self::PLURAL)
		{

			$this->config['list_name'] = $this->listName ? : $name;
			$this->config['item_name'] = $this->itemName ? : $inflector->toSingular($this->config['list_name']);
		}

		parent::__construct($input, $package, $container);
	}

	/**
	 * getUserState
	 *
	 * @param string $name
	 * @param mixed  $default
	 * @param string $namespace
	 *
	 * @return mixed
	 */
	public function getUserState($name, $default = null, $namespace = 'default')
	{
		return $this->app->session->get($name, $default, $namespace);
	}

	/**
	 * setUserState
	 *
	 * @param string $name
	 * @param mixed  $value
	 * @param string $namespace
	 *
	 * @return  static
	 */
	public function setUserState($name, $value, $namespace = 'default')
	{
		$this->app->session->set($name, $value, $namespace);

		return $this;
	}

	/**
	 * removeUserState
	 *
	 * @param string $name
	 * @param string $namespace
	 *
	 * @return  static
	 */
	public function removeUserState($name, $namespace = 'default')
	{
		$this->app->session->remove($name, $namespace);

		return $this;
	}

	/**
	 * Gets the value from session and input and sets it back to session
	 *
	 * @param string $name
	 * @param string $inputName
	 * @param mixed  $default
	 * @param string $filter
	 * @param string $namespace
	 *
	 * @return  mixed
	 */
	public function getUserStateFromInput($name, $inputName, $default = null, $filter = InputFilter::STRING, $namespace = 'default')
	{
		$oldState = $this->getUserState($name, $default, $namespace);
		$newState = $this->input->get($inputName, null, $filter);

		if ($newState !== null)
		{
			$this->setUserState($name, $newState, $namespace);

			return $newState;
		}

		return $oldState;
	}

	/**
	 * getContext
	 *
	 * @param   string $task
	 *
	 * @return  string
	 */
	public function getContext($task = null)
	{
		$context = $this->package->getName() . '.' . strtolower($this->getName());

		if ($task)
		{
			$context .= '.' . $task;
		}

		return $context;
	}

	/**
	 * Check user has access to view this page.
	 *
	 * Throw exception with 4xx code to block unauthorised access.
	 *
	 * @param   array|DataInterface  $data
	 *
	 * @return  boolean
	 *
	 * @throws \RuntimeException
	 * @throws RouteNotFoundException
	 * @throws UnauthorizedException
	 *
	 * @deprecated Use authorise() instead.
	 */
	public function checkAccess($data)
	{
		return true;
	}

	/**
	 * Check user has access to view this page.
	 *
	 * Throw exception with 4xx code to block unauthorised access.
	 *
	 * @return  void
	 *
	 * @throws \RuntimeException
	 * @throws RouteNotFoundException
	 * @throws UnauthorizedException
	 */
	public function authorise()
	{
	}

	/**
	 * getModel
	 *
	 * @param string $name
	 * @param mixed  $source
	 * @param bool   $forceNew
	 *
	 * @return  ModelRepository
	 */
	public function getModel($name = null, $source = null, $forceNew = false)
	{
		if ($name)
		{
			return parent::getModel($name, $source, $forceNew);
		}

		if (!$this->model instanceof ModelRepository || $forceNew)
		{
			$this->model = parent::getModel($this->model, $source, $forceNew);
		}

		return $this->model;
	}
}
