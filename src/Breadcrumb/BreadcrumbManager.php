<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Breadcrumb;

use Traversable;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;

/**
 * The Breadcrumbs class.
 *
 * @since  {DEPLOY_VERSION}
 */
class BreadcrumbManager implements \IteratorAggregate
{
    /**
     * Property items.
     *
     * @var DataSet
     */
    protected $items;

    /**
     * BreadcrumbManager constructor.
     *
     * @param Data[]|DataSet $items
     */
    public function __construct($items = [])
    {
        $this->setItems($items);
    }

    /**
     * addPath
     *
     * @param string $title
     * @param string $link
     * @param bool   $active
     *
     * @return  static
     */
    public function push($title, $link = null, $active = false)
    {
        $this->items->push(
            new Data(
                [
                    'title' => $title,
                    'link' => $link,
                    'active' => $active
                ]
            )
        );

        return $this;
    }

    /**
     * pop
     *
     * @return  Data
     *
     * @since  3.3
     */
    public function pop()
    {
        return $this->items->pop();
    }

    /**
     * get
     *
     * @param string|int $key
     *
     * @return  Data
     *
     * @since  3.3
     */
    public function get($key)
    {
        return $this->items->get($key);
    }

    /**
     * set
     *
     * @param string|int $key
     * @param Data       $item
     *
     * @return  static
     *
     * @since  3.3
     */
    public function set($key, Data $item)
    {
        $this->items->set($key, $item);

        return $this;
    }

    /**
     * map
     *
     * @param callable $callback
     *
     * @return  static
     *
     * @since  3.3
     */
    public function map(callable $callback)
    {
        $this->items = $this->items->map($callback);

        return new static($this->items);
    }

    /**
     * render
     *
     * @param array $params
     *
     * @return  string
     */
    public function render(array $params = [])
    {
        $params['paths'] = $this;

        return WidgetHelper::render(
            'phoenix.bootstrap.ui.breadcrumbs',
            $params,
            'edge'
        );
    }

    /**
     * Method to get property Items
     *
     * @return  DataSet
     *
     * @since  3.3
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Method to set property items
     *
     * @param   DataSet $items
     *
     * @return  static  Return self to support chaining.
     *
     * @since  3.3
     */
    public function setItems($items)
    {
        $this->items = $items ?: new DataSet($items);

        return $this;
    }

    /**
     * Retrieve an external iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->items;
    }
}
