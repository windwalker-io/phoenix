<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Minify;

use JSMin;

/**
 * The JsMinify class.
 *
 * @since  {DEPLOY_VERSION}
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
		$this->asset->setScripts(array());
		$this->asset->setInternalScripts(null);

		$this->asset->addScript($path, $md5sum);
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
		return JSMin::minify($data);
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
