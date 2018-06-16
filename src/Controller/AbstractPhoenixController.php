<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Repository\Repository;
use Windwalker\DI\Container;
use Windwalker\Filter\InputFilter;
use Windwalker\IO\Input;
use Windwalker\String\StringInflector;

/**
 * The AbstractRadController class.
 *
 * @since  1.0
 */
abstract class AbstractPhoenixController extends AbstractController
{
    const SINGULAR = 'singular';

    const PLURAL = 'plural';

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
     * The default Repository.
     *
     * If set model name here, controller will get model object by this name.
     *
     * @var  Repository
     */
    protected $repository;

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
     * @param AbstractPackage $package
     * @param Container       $container
     *
     * @throws \ReflectionException
     */
    public function __construct(Input $input = null, AbstractPackage $package = null, Container $container = null)
    {
        $this->config = $this->getConfig();

        $name = $this->getName();

        // Guess name
        $inflector = StringInflector::getInstance();

        if ($this->inflection == self::SINGULAR) {
            $this->config['item_name'] = $this->itemName ?: $name;
            $this->config['list_name'] = $this->listName ?: $inflector->toPlural($this->config['item_name']);
        } elseif ($this->inflection == self::PLURAL) {
            $this->config['list_name'] = $this->listName ?: $name;
            $this->config['item_name'] = $this->itemName ?: $inflector->toSingular($this->config['list_name']);
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
    public function getUserStateFromInput(
        $name,
        $inputName,
        $default = null,
        $filter = InputFilter::STRING,
        $namespace = 'default'
    ) {
        $oldState = $this->getUserState($name, $default, $namespace);
        $newState = $this->input->get($inputName, null, $filter);

        if ($newState !== null) {
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

        if ($task) {
            $context .= '.' . $task;
        }

        return $context;
    }

    /**
     * Check user has access to view this page.
     *
     * Throw exception with 4xx code to block unauthorised access.
     *
     * @return  bool Return FALSE if use has no access to view page.
     *
     * @throws \RuntimeException
     * @throws \Windwalker\Router\Exception\RouteNotFoundException (404)
     * @throws \Windwalker\Core\Security\Exception\UnauthorizedException (401 / 403)
     */
    public function authorise()
    {
        return true;
    }

    /**
     * getRepository
     *
     * @param string $name
     * @param mixed  $source
     * @param bool   $forceNew
     *
     * @return  Repository
     * @throws \Exception
     */
    public function getRepository($name = null, $source = null, $forceNew = false)
    {
        if ($name) {
            return parent::getRepository($name, $source, $forceNew);
        }

        if (!$this->repository instanceof Repository || $forceNew) {
            $this->repository = parent::getRepository($this->repository ?: $this->model, $source, $forceNew);
        }

        return $this->repository;
    }

    /**
     * __get
     *
     * @param   string $name
     *
     * @return  mixed
     *
     * @throws \OutOfRangeException
     */
    public function __get($name)
    {
        if ($name === 'model') {
            return $this->repository;
        }

        return parent::__get($name);
    }
}
