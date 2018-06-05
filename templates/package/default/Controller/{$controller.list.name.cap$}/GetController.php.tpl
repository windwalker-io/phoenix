<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\Controller\{$controller.list.name.cap$};

use {$package.namespace$}{$package.name.cap$}\Repository\{$controller.list.name.cap$}Repository;
use {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$}\{$controller.list.name.cap$}HtmlView;
use Phoenix\Controller\Display\ListDisplayController;
use Windwalker\Core\Repository\Repository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class GetController extends ListDisplayController
{
    /**
     * The default Repository.
     *
     * If set model name here, controller will get model object by this name.
     *
     * @var  {$controller.list.name.cap$}Repository
     */
    protected $repository = '{$controller.list.name.cap$}';

    /**
     * Main View.
     *
     * If set view name here, controller will get model object by this name.
     *
     * @var  {$controller.list.name.cap$}HtmlView
     */
    protected $view = '{$controller.list.name.cap$}';

    /**
     * Property ordering.
     *
     * Please remember add table alias.
     *
     * @var  string
     */
    protected $defaultOrdering = '{$controller.item.name.lower$}.id';

    /**
     * Property direction.
     *
     * @var  string
     */
    protected $defaultDirection = 'DESC';

    /**
     * The list limit per page..
     *
     * Use 0 to set unlimited.
     *
     * @var integer
     */
    protected $limit;

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
        return parent::authorise();
    }

    /**
     * A hook before main process executing.
     *
     * @return  void
     */
    protected function prepareExecute()
    {
        $this->layout = $this->input->get('layout');
        $this->format = $this->input->get('format', 'html');

        parent::prepareExecute();
    }

    /**
     * Prepare view and default repository.
     *
     * You can configure default model state here, or add more sub models to view.
     * Remember to call parent to make sure default model already set in view.
     *
     * @param AbstractView $view       The view to render page.
     * @param Repository   $repository The default repository.
     *
     * @return  void
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function prepareViewRepository(AbstractView $view, Repository $repository)
    {
        /**
         * @var $view       {$controller.list.name.cap$}HtmlView
         * @var $repository {$controller.list.name.cap$}Repository
         */
        parent::prepareViewRepository($view, $repository);

        // Configure view and repository here...
    }

    /**
     * A hook after main process executing.
     *
     * @param mixed $result The result content to return, can be any value or boolean.
     *
     * @return  mixed
     */
    protected function postExecute($result = null)
    {
        return parent::postExecute($result);
    }
}
