<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Data\Data;
use Windwalker\Dom\HtmlElement;
use function Windwalker\h;

/**
 * The Metadata class.
 *
 * @since  1.1
 */
class Metadata extends Data
{
    /**
     * Property metadata.
     *
     * @var  array
     */
    protected $metadata = [];

    /**
     * Property openGraphs.
     *
     * @var  array
     */
    protected $openGraphs = [];

    /**
     * addMetadata
     *
     * @param   string $name
     * @param   string $content
     * @param   bool   $replace
     *
     * @return static
     */
    public function addMetadata($name, $content, $replace = false)
    {
        if (!isset($this->metadata[$name]) || $replace) {
            $this->metadata[$name] = [];
        }

        if (is_stringable($content)) {
            $content = (string) $content;
        }

        foreach ((array) $content as $item) {
            $this->metadata[$name][] = h('meta', [
                'name' => $this->escape($name),
                'content' => $this->escape($item),
            ]);
        }

        return $this;
    }

    /**
     * removeMetadata
     *
     * @param  string $name
     *
     * @return  static
     */
    public function removeMetadata($name)
    {
        if (isset($this->metadata[$name])) {
            unset($this->metadata[$name]);
        }

        return $this;
    }

    /**
     * addOpenGraph
     *
     * @param   string $type
     * @param   string $content
     * @param   bool   $replace
     *
     * @return static
     */
    public function addOpenGraph($type, $content, $replace = false)
    {
        if (!isset($this->openGraphs[$type]) || $replace) {
            $this->openGraphs[$type] = [];
        }

        if (is_stringable($content)) {
            $content = (string) $content;
        }

        foreach ((array) $content as $item) {
            $this->openGraphs[$type][] = h('meta', [
                'property' => $this->escape($type),
                'content' => $this->escape($item),
            ]);
        }

        return $this;
    }

    /**
     * removeOpenGraph
     *
     * @param  string $type
     *
     * @return  static
     */
    public function removeOpenGraph($type)
    {
        if (isset($this->openGraphs[$type])) {
            unset($this->openGraphs[$type]);
        }

        return $this;
    }

    /**
     * Method to get property Metadata
     *
     * @return  array
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * Method to set property metadata
     *
     * @param   array $metadata
     *
     * @return  static  Return self to support chaining.
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * Method to get property OpenGraphs
     *
     * @return  array
     */
    public function getOpenGraphs()
    {
        return $this->openGraphs;
    }

    /**
     * Method to set property openGraphs
     *
     * @param   array $openGraphs
     *
     * @return  static  Return self to support chaining.
     */
    public function setOpenGraphs(array $openGraphs)
    {
        $this->openGraphs = $openGraphs;

        return $this;
    }

    /**
     * escape
     *
     * @param   string $string
     *
     * @return  string
     */
    public function escape($string)
    {
        return htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
    }
}
