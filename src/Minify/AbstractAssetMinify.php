<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use Phoenix\Asset\AssetManager;
use Phoenix\Uri\Uri;
use Windwalker\Environment\ServerHelper;
use Windwalker\Filesystem\Folder;
use Windwalker\Http\HttpClient;
use Windwalker\Utilities\ArrayHelper;

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
	 * Constructor.
	 *
	 * @param AssetManager $asset
	 */
	public function __construct($asset)
	{
		$this->asset = $asset;
	}

	/**
	 * compress
	 *
	 * @return  void
	 */
	public function compress()
	{
		// Get assets list from Document
		$list = $this->getStorage();

		$list = ArrayHelper::getColumn($list, 'url');

		// Build assets hash per page.
		$name = $this->buildHash($list);

		// Cache file path.
		$path = $this->getCachePath($name);

		$assetPath = WINDWALKER_PUBLIC . '/media/' . $path;

		// Prepare to minify and combine files.
		if (!is_file($assetPath))
		{
			// Combine data by file list.
			$data = $this->combineData($list);
			$data = $this->doCompress($data);

			if (!is_dir(dirname($assetPath)))
			{
				Folder::create(dirname($assetPath));
			}

			file_put_contents($assetPath, $data);
		}

		$this->addAsset($path);
	}

	/**
	 * doCompress
	 *
	 * @param string $data
	 *
	 * @return  string
	 */
	abstract protected function doCompress($data);

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
	 * @param array  $list
	 *
	 * @return  string
	 */
	protected function buildHash($list)
	{
		$hash = '';

		foreach ($list as $name)
		{
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
	 * combineData
	 *
	 * @param array  $list
	 *
	 * @return  string
	 */
	protected function combineData($list)
	{
		$data = array();

		foreach ($list as $url)
		{
			$data[] = $this->prepareAssetData($url);
		}

		return $this->implodeData($data) . "\n" . $this->getInternalStorage();
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
		if (ServerHelper::isWindows())
		{
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
		// Convert url to local path.
		if (substr($file, 0, 1) == '/')
		{
			$file = Uri::host() . $file;
		}
		// Absolute path.
		elseif (substr($file, 0, 4) != 'http')
		{
			$file = Uri::root() . ltrim($file, '/');
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
