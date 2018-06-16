<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Controller\Middleware\JsonApiMiddleware;
use Windwalker\Core\Repository\Repository;
use Windwalker\Core\Response\HtmlViewResponse;
use Windwalker\Core\Security\Exception\UnauthorizedException;
use Windwalker\Core\View\AbstractView;
use Windwalker\Core\View\HtmlView;
use Windwalker\Core\View\LayoutRenderableInterface;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Http\Response;

/**
 * The AbstractGetController class.
 *
 * @since  1.0
 */
class DisplayController extends AbstractPhoenixController
{
    /**
     * Main View.
     *
     * If set view name here, controller will get model object by this name.
     *
     * @var  HtmlView
     */
    protected $view;

    /**
     * Format for rendering.
     *
     * @var  string
     */
    protected $format;

    /**
     * Layout name if is HTML format.
     *
     * @var  string
     */
    protected $layout;

    /**
     * B/C for old usage.
     *
     * @var Repository
     *
     * @deprecated  Use $repository instead,
     */
    protected $model;

    /**
     * A hook before main process executing.
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareExecute()
    {
        $this->format = $this->format ?: $this->app->get('route.extra.format', 'html');
        $this->layout = $this->layout ?: $this->app->get('route.extra.layout', $this->name);

        $this->repository = $this->model = $this->getRepository();
        $this->view       = $this->getView();

        // Prepare response
        $response = $this->response;

        $this->response = $this::createResponse(
            $this->format,
            $response->getBody()->__toString(),
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getReasonPhrase()
        );

        // Prepare Json Middleware
        if ($this->format === 'json'
            && !in_array(JsonApiMiddleware::class, iterator_to_array(clone $this->middlewares), true)) {
            $this->addMiddleware(JsonApiMiddleware::class);
        }
    }

    /**
     * The main execution process.
     *
     * @return  mixed
     * @throws \RuntimeException
     */
    protected function doExecute()
    {
        $this->prepareViewRepository($this->view, $this->repository);

        if (!$this->authorise()) {
            throw new UnauthorizedException('You have no access to view this page.');
        }

        if ($this->view instanceof LayoutRenderableInterface) {
            $this->view->setLayout($this->layout);
        } // Only show debugger in HTML view
        elseif (class_exists(DebuggerHelper::class)) {
            DebuggerHelper::disableConsole();
        }

        return $this->view;
    }

    /**
     * getView
     *
     * @param string $name
     * @param string $format
     * @param string $engine
     * @param bool   $forceNew
     *
     * @return AbstractView|HtmlView
     *
     * @throws \UnexpectedValueException
     * @throws \Exception
     */
    public function getView($name = null, $format = null, $engine = null, $forceNew = false)
    {
        $format = $format ?: $this->format;

        if ($name) {
            return parent::getView($name, $format, $engine, $forceNew);
        }

        if (!$this->view instanceof AbstractView || $forceNew) {
            $this->view = parent::getView($this->view, $format, $engine, $forceNew);
        }

        return $this->view;
    }

    /**
     * Prepare view and default model.
     *
     * You can configure default model state here, or add more sub models to view.
     * Remember to call parent to make sure default model already set in view.
     *
     * @param AbstractView $view  The view to render page.
     * @param Repository   $model The default mode.
     *
     * @return  void
     *
     * @throws \ReflectionException
     * @deprecated Use prepareViewRepository() instead.
     */
    protected function prepareViewModel(AbstractView $view, Repository $model)
    {
        // Here is B/C code
        $this->view->setRepository($this->repository, true, function (Repository $model) {
            $this->prepareModelState($model);
        });

        $this->assignModels($this->view);

        $this->prepareViewData($this->view);
        // Add your logic after parent...
    }

    /**
     * Prepare view and default repository.
     *
     * You can configure default repository state here, or add more sub repositories to view.
     * Remember to call parent to make sure default repository already set in view.
     *
     * @param AbstractView $view       The view to render page.
     * @param Repository   $repository The default repository.
     *
     * @return  void
     *
     * @throws \ReflectionException
     * @since  1.6
     */
    protected function prepareViewRepository(AbstractView $view, Repository $repository)
    {
        $this->prepareViewModel($view, $repository);
    }

    /**
     * assignModels
     *
     * @param AbstractView $view
     *
     * @return  void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function assignModels(AbstractView $view)
    {
        // Implement it.
    }

    /**
     * prepareUserState
     *
     * @param   Repository $model
     *
     * @return  void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function prepareModelState(Repository $model)
    {
    }

    /**
     * prepareViewData
     *
     * @param   AbstractView $view
     *
     * @return  void
     *
     * @deprecated Override prepareViewModel() instead.
     */
    protected function prepareViewData(AbstractView $view)
    {
    }

    /**
     * Method to get property Layout
     *
     * @return  string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Method to set property layout
     *
     * @param   string $layout
     *
     * @return  static  Return self to support chaining.
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Method to get property Format
     *
     * @return  string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Method to set property format
     *
     * @param   string $format
     *
     * @return  static  Return self to support chaining.
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * createResponse
     *
     * @param string $format
     * @param string $body
     * @param int    $code
     * @param array  $headers
     * @param string $reason
     *
     * @return Response\AbstractContentTypeResponse
     */
    public static function createResponse($format, $body, $code, $headers, $reason = null)
    {
        switch (strtolower($format)) {
            case 'html':
                $response = new HtmlViewResponse($body, $code, $headers);
                break;

            case 'json':
                $response = new Response\JsonResponse($body, $code, $headers);
                break;

            case 'xml':
                $response = new Response\XmlResponse($body, $code, $headers);
                break;

            default:
                $response = new Response\TextResponse($body, $code, $headers);
        }

        if ($reason !== null) {
            $response = $response->withStatus($code, $reason);
        }

        return $response;
    }
}
