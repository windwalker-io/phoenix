<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller;

use Phoenix\Repository\CrudRepository;
use Phoenix\Repository\CrudRepositoryInterface;
use Windwalker\Core\Controller\Traits\CsrfProtectionTrait;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Repository\DatabaseRepositoryInterface;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DataMapper\Entity\Entity;
use Windwalker\Http\Response\HtmlResponse;
use Windwalker\Http\Response\RedirectResponse;
use Windwalker\Uri\Uri;

/**
 * The AbstractAdminController class.
 *
 * @method  CrudRepository  getModel($name = null, $source = null, $forceNew = false)
 *
 * @since  1.0
 */
abstract class AbstractPostController extends AbstractPhoenixController
{
    use CsrfProtectionTrait;

    /**
     * Property model.
     *
     * @var  CrudRepository
     */
    protected $repository;

    /**
     * Property model.
     *
     * @var CrudRepository
     *
     * @deprecated  Use $repository instead.
     */
    protected $model;

    /**
     * Property data.
     *
     * @var  array
     */
    protected $data = [];

    /**
     * Property task.
     *
     * @var  string
     */
    protected $task;

    /**
     * Property entity.
     *
     * @var  DataInterface|Entity
     */
    protected $dataObject;

    /**
     * Property keyName.
     *
     * @var  string
     */
    protected $keyName = null;

    /**
     * The query will auto add to redirect url.
     *
     * @var  array
     */
    protected $redirectQueryFields = [
        'return',
    ];

    /**
     * A hook before main process executing.
     *
     * @return  void
     *
     * @throws \Windwalker\Router\Exception\RouteNotFoundException
     * @throws \RuntimeException
     * @throws \Windwalker\Core\Security\Exception\UnauthorizedException
     * @throws \LogicException
     * @throws \DomainException
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();

        $this->repository = $this->model = $this->getModel();
        $this->dataObject = $this->getDataObject();
        $this->task       = $this->input->get('task');

        // Determine model
        if (!$this->repository instanceof CrudRepositoryInterface) {
            throw new \DomainException(sprintf(
                '%s model should be instance of %s, class: %s given.',
                $this->repository->getName(),
                CrudRepositoryInterface::class,
                get_class($this->repository)
            ));
        }

        // Determine the name of the primary key for the data.
        if (empty($this->keyName) && $this->repository instanceof DatabaseRepositoryInterface) {
            $this->keyName = $this->repository->getKeyName(false) ?: 'id';
        }
    }

    /**
     * Check user has access to modify this resource or not.
     *
     * Throw exception with 4xx code or return false to block unauthorised access.
     *
     * @param   array|DataInterface $data
     *
     * @return  boolean
     *
     * @throws \RuntimeException
     * @throws \Windwalker\Core\Security\Exception\UnauthorizedException (401 / 403)
     */
    public function checkAccess($data)
    {
        return true;
    }

    /**
     * Process success.
     *
     * @param  mixed $result
     *
     * @return mixed
     */
    public function processSuccess($result)
    {
        $this->removeUserState($this->getContext('edit.data'));

        $entity = $this->getDataObject();

        $this->addMessage($this->getSuccessMessage($entity), Bootstrap::MSG_SUCCESS);

        $this->setRedirect($this->getSuccessRedirect($entity));

        if (!$this->response instanceof HtmlResponse && !$this->response instanceof RedirectResponse) {
            return $entity->dump(true);
        }

        return $result;
    }

    /**
     * Process failure.
     *
     * @param \Exception $e
     *
     * @return bool
     */
    public function processFailure(\Exception $e = null)
    {
        $this->setUserState($this->getContext('edit.data'), $this->data);

        $this->addMessage($e->getMessage(), Bootstrap::MSG_WARNING);

        $this->setRedirect($this->getFailRedirect($this->getDataObject()));

        return false;
    }

    /**
     * getSuccessMessage
     *
     * @param Data $data
     *
     * @return  string
     */
    public function getSuccessMessage($data = null)
    {
        return '';
    }

    /**
     * getSuccessRedirect
     *
     * @param  DataInterface|Entity $data
     *
     * @return  string
     */
    protected function getSuccessRedirect(DataInterface $data = null)
    {
        $uri = new Uri($this->app->uri->full);

        foreach ($this->getRedirectQuery() as $field => $value) {
            $uri->setVar($field, $value);
        }

        return $uri->toString();
    }

    /**
     * getFailRedirect
     *
     * @param  DataInterface $data
     *
     * @return  string
     */
    protected function getFailRedirect(DataInterface $data = null)
    {
        return $this->getSuccessRedirect($data);
    }

    /**
     * getRedirectQuery
     *
     * @param array $query
     *
     * @return  array
     */
    protected function getRedirectQuery($query = [])
    {
        foreach ((array) $this->redirectQueryFields as $field) {
            $query[$field] = $this->input->getString($field);
        }

        return $query;
    }

    /**
     * Method to get property Data
     *
     * @return  array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Method to set property data
     *
     * @param   array $data
     *
     * @return  static  Return self to support chaining.
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Method to get property Entity
     *
     * @return  DataInterface|Entity
     */
    public function getDataObject()
    {
        if (!$this->dataObject) {
            return $this->dataObject = new Data();
        }

        return $this->dataObject;
    }

    /**
     * Method to set property entity
     *
     * @param   DataInterface|Entity $dataObject
     *
     * @return  static  Return self to support chaining.
     */
    public function setDataObject(DataInterface $dataObject)
    {
        $this->dataObject = $dataObject;

        return $this;
    }
}
