<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use Asika\Minifier\CssMinifier;
use Asika\Minifier\MinifierInterface;

/**
 * The CssMinify class.
 *
 * @since  1.0
 */
class CssMinify extends AbstractAssetMinify
{
    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = 'css';

    /**
     * getMinifier
     *
     * @return  MinifierInterface
     */
    protected function createMinifier()
    {
        return new CssMinifier;
    }

    /**
     * getStorage
     *
     * @return  array
     */
    protected function getStorage()
    {
        return $this->asset->getStyles();
    }

    /**
     * getInternalStorage
     *
     * @return  array
     */
    protected function getInternalStorage()
    {
        return implode("\n", $this->asset->getInternalStyles());
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
        $this->asset->setStyles([]);
        $this->asset->setInternalStyles(null);

        $this->asset->addStyle($path, $md5sum);
    }

    /**
     * handleCssFile
     *
     * @param string $file
     * @param string $url
     *
     * @return  string
     */
    protected function handleFile($file, $url)
    {
        $path = dirname($url);
        $path = str_replace($this->asset->uri->root, WINDWALKER_PUBLIC, $path);

        // Rewrite Url
        $newFile = CssUriRewriter::rewrite(
            $file,
            $path,
            $_SERVER['DOCUMENT_ROOT']
        );

        // Handle Imports
        $newFile = preg_replace_callback(
            '/@import\\s*url\(([^)]+)\)/',
            function ($matches) {
                return $this->prepareAssetData(trim($matches[1], "\"'"));
            },
            $newFile
        );

        return $newFile;
    }
}
