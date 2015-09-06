<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Controller\Controller;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\DI\Container;
use Windwalker\Filter\InputFilter;
use Windwalker\IO\Input;
use Windwalker\String\StringInflector;

/**
 * The AbstractRadController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractPhoenixController extends Controller
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
	 * Property prefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'phoenix.';

	/**
	 * Class init.
	 *
	 * @param Input           $input
	 * @param WebApplication  $app
	 * @param Container       $container
	 * @param AbstractPackage $package
	 */
	public function __construct(Input $input = null, WebApplication $app = null, Container $container = null, AbstractPackage $package = null)
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

		parent::__construct($input, $app, $container, $package);
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
		$context = $this->package->getName() . '.' . $this->getName();

		if ($task)
		{
			$context .= '.' . $task;
		}

		return $context;
	}

	/**
	 * checkToken
	 *
	 * @return  boolean
	 */
	protected function checkToken()
	{
		return CsrfProtection::checkToken();
	}
}
