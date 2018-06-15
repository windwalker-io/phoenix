<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Breadcrumb;

use Traversable;
use Windwalker\Core\Facade\AbstractProxyFacade;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;

/**
 * The Breadcrumb class.
 *
 * @see    BreadcrumbManager
 *
 * @method  static BreadcrumbManager  push(string $title, string $link = null, bool $active = false)
 * @method  static Data               pop()
 * @method  static BreadcrumbManager  map(callable $callback)
 * @method  static string             render()
 * @method  static DataSet            getItems()
 * @method  static BreadcrumbManager  setItems($items)
 * @method  static Traversable        getIterator()
 * @method  static Data               get($key)
 * @method  static BreadcrumbManager  set($key, Data $item)
 *
 * @since  3.3
 */
class Breadcrumb extends AbstractProxyFacade
{
    /**
     * Property _key.
     *
     * @var  string
     */
    protected static $_key = 'breadcrumb';
}
