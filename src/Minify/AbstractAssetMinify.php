<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use Asika\Minifier\AbstractMinifier;
use Asika\Minifier\MinifierInterface;
use Windwalker\Core\Asset\AssetManager;
use Windwalker\Environment\PlatformHelper;
use Windwalker\Filesystem\Exception\FilesystemException;
use Windwalker\Filesystem\File;
use Windwalker\Http\HttpClient;

/**
 * The AbstractAssetMinify class.
 *
 * @since  1.0
 */
abstract class AbstractAssetMinify
{
    /**
     * Property doc.
     *
     * @var  AssetManager
     */
    protected $asset = null;

    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = '';

    /**
     * Property minifier.
     *
     * @var  MinifierInterface
     */
    protected $minifier;

    /**
     * Property options.
     *
     * @var  array
     */
    protected $minifyOptions = [AbstractMinifier::FLAGGED_COMMENTS => false];

    /**
     * Constructor.
     *
     * @param AssetManager $asset
     */
    public function __construct(AssetManager $asset)
    {
        $this->asset = $asset;
    }

    /**
     * compress
     *
     * @return  void
     *
     * @throws FilesystemException
     */
    public function compress()
    {
        // Get assets list from Document
        $list = $this->getStorage();

        $list = array_column($list, 'url');

        // Build assets hash per page.
        $name = $this->buildHash($list);

        // Cache file path.
        $path = $this->getCachePath($name);

        $assetPath = WINDWALKER_PUBLIC . '/' . $this->asset->getAssetFolder() . '/' . $path;

        // Prepare to minify and combine files.
        if (!is_file($assetPath)) {
            // Combine data by file list.
            $minify = $this->getMinifier();

            foreach ($list as $url) {
                $minify->addContent($this->prepareAssetData($url), $this->minifyOptions);
            }

            $minify->addContent($this->getInternalStorage(), $this->minifyOptions);

            File::write($assetPath, $minify->minify());
        }

        $this->addAsset($path);
    }

    /**
     * getMinifier
     *
     * @return  MinifierInterface
     */
    abstract protected function createMinifier();

    /**
     * getMinifier
     *
     * @return  MinifierInterface
     */
    protected function getMinifier()
    {
        if (!$this->minifier) {
            $this->minifier = $this->createMinifier();
        }

        return $this->minifier;
    }

    /**
     * getStorage
     *
     * @return  mixed
     */
    abstract protected function getStorage();

    /**
     * getInterStorage
     *
     * @return  mixed
     */
    abstract protected function getInternalStorage();

    /**
     * addAsset
     *
     * @param string $path
     * @param string $md5sum
     *
     * @return  mixed
     */
    abstract protected function addAsset($path, $md5sum = null);

    /**
     * buildHash
     *
     * @param array $list
     *
     * @return  string
     */
    protected function buildHash($list)
    {
        $hash = '';

        foreach ($list as $name) {
            $hash .= $name;
        }

        $hash .= $this->getInternalStorage();

        return md5($hash) . '.' . $this->type;
    }

    /**
     * getCachePath
     *
     * @param string $hash
     *
     * @return  string
     */
    protected function getCachePath($hash)
    {
        return 'min/' . $hash;
    }

    /**
     * prepareAssetData
     *
     * @param string $url
     *
     * @return  string
     */
    public function prepareAssetData($url)
    {
        // Convert url to relative path
        $file = $this->regularizeUrl($url);

        // Init Http
        if (PlatformHelper::isWindows()) {
            // $file = str_replace('localhost', '127.0.0.1', $file);
        }

        $http = new HttpClient;

        $content = $http->get($file)->getBody()->getContents();

        // Using handle method to prepare file
        $content = $this->handleFile($content, $file);

        return "\n\n/* File: {$url} */\n\n" . $content;
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
        return implode("\n\n", $data);
    }

    /**
     * regularizeUrl
     *
     * @param string $file
     *
     * @return  string
     */
    protected function regularizeUrl($file)
    {
        // Remote or absolute path
        if (strpos($file, 'http') === 0 || strpos($file, '//') === 0) {
            return $this->asset->uri->path . '/' . ltrim($file, '/');
        }

        // Convert url to local path.
        if (isset($file[0]) && $file[0] === '/') {
            return $this->asset->uri->host . '/' . ltrim($file, '/');
        }

        return $file;
    }

    /**
     * handleFile
     *
     * @param string $file
     * @param string $url
     *
     * @return  string
     */
    protected function handleFile($file, $url)
    {
        return $file;
    }
}
