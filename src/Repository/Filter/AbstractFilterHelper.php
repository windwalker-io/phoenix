<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Phoenix\Repository\Filter;

/**
 * Abstract Filter Helper
 *
 * @since 1.0
 */
abstract class AbstractFilterHelper implements FilterHelperInterface
{
    /**
     * Skip this filter.
     *
     * @const boolean
     */
    const SKIP = false;

    /**
     * Handler callbacks.
     *
     * @var  array
     */
    protected $handler = [];

    /**
     * The default handler.
     *
     * @var  \Closure
     */
    protected $defaultHandler = null;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->defaultHandler = $this->getDefaultHandler();
    }

    /**
     * Set filter handler. Can be a callback or closure.
     *
     * Example:
     * ``` php
     * function(\JDatabaseQuery $query, $field, $value)
     * {
     *     return $query->where($field . ' <= ' . $value);
     * }
     * ```
     *
     * @param string        $name    The handler name.
     * @param callable|bool $handler Handler callback.
     *
     * @return  AbstractFilterHelper Return self to support chaining.
     */
    public function setHandler($name, $handler)
    {
        $this->handler[$name] = $handler;

        return $this;
    }

    /**
     * Register the default handler.
     *
     * @return  callable The handler callback.
     */
    abstract protected function getDefaultHandler();

    /**
     * Set default handler.
     *
     * @param   callable $defaultHandler The default handler.
     *
     * @return  AbstractFilterHelper  Return self to support chaining.
     */
    public function setDefaultHandler(callable $defaultHandler)
    {
        $this->defaultHandler = $defaultHandler;

        return $this;
    }
}
