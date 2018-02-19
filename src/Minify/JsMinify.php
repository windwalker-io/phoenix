<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use Asika\Minifier\JsMinifier;
use Asika\Minifier\MinifierInterface;

/**
 * The JsMinify class.
 *
 * @since  1.0
 */
class JsMinify extends AbstractAssetMinify
{
    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = 'js';

    /**
     * getStorage
     *
     * @return  array
     */
    protected function getStorage()
    {
        return $this->asset->getScripts();
    }

    /**
     * getMinifier
     *
     * @return  MinifierInterface
     */
    protected function createMinifier()
    {
        return new JsMinifier;
    }

    /**
     * getInternalStorage
     *
     * @return  array
     */
    protected function getInternalStorage()
    {
        return implode("\n", $this->asset->getInternalScripts());
    }

    /**
     * addAsset
     *
     * @param string $path
     * @param string $md5sum
     *
     * @return  void
     */
    protected function addAsset($path, $md5sum = null)
    {
        // Clean assets list
        $this->asset->setScripts([]);
        $this->asset->setInternalScripts(null);

        $this->asset->addScript($path, $md5sum);
    }

    /**
     * implodeData
     *
     * @param array $data
     *
     * @return  string
     */
    protected function implodeData($data)
    {
        return implode("\n;\n", $data);
    }
}
