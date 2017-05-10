<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use Minify_CSS_Compressor;
use Minify_CSS_UriRewriter;
use Phoenix\Uri\Uri;

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
	 * doCompress
	 *
	 * @param string $data
	 *
	 * @return  string
	 */
	protected function doCompress($data)
	{
		return Minify_CSS_Compressor::process($data);
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
		$that = $this;

		$path = dirname($url);
		$path = str_replace($this->asset->uri->root, WINDWALKER_PUBLIC, $path);

		// Rewrite Url
		$newFile = Minify_CSS_UriRewriter::rewrite(
			$file,
			$path,
			$_SERVER['DOCUMENT_ROOT']
		);

		// Handle Imports
		$newFile = preg_replace_callback(
			'/@import\\s*url\(([^)]+)\)/',
			function ($matches) use ($that)
			{
				return $that->prepareAssetData(trim($matches[1], "\"'"));
			},
			$newFile
		);

		return $newFile;
	}
}
