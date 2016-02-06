<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Asset;

use Phoenix\Uri\Uri;
use Windwalker\Dom\HtmlElement;
use Windwalker\Environment\ServerHelper;
use Windwalker\Filesystem\File;
use Windwalker\Filesystem\Filesystem;
use Windwalker\Ioc;
use Windwalker\String\StringHelper;
use Windwalker\Utilities\ArrayHelper;

/**
 * The AssetManager class.
 * 
 * @since  1.0
 */
class AssetManager
{
	/**
	 * Property styles.
	 *
	 * @var  array
	 */
	protected $styles = array();

	/**
	 * Property scripts.
	 *
	 * @var  array
	 */
	protected $scripts = array();

	/**
	 * Property internalStyles.
	 *
	 * @var  array
	 */
	protected $internalStyles = array();

	/**
	 * Property internalScripts.
	 *
	 * @var  array
	 */
	protected $internalScripts = array();

	/**
	 * Property version.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Property indents.
	 *
	 * @var  string
	 */
	protected $indents = '    ';

	/**
	 * addStyle
	 *
	 * @param string $url
	 * @param string $version
	 * @param array  $attribs
	 *
	 * @return  static
	 */
	public function addStyle($url, $version = null, $attribs = array())
	{
		if (!$version && $version !== false)
		{
			$version = $this->getVersion();
		}

		$file = array(
			'url' => $this->handleUri($url),
			'attribs' => $attribs,
			'version' => $version
		);

		$this->styles[$url] = $file;

		return $this;
	}

	/**
	 * addScript
	 *
	 * @param string $url
	 * @param string $version
	 * @param array  $attribs
	 *
	 * @return  static
	 */
	public function addScript($url, $version = null, $attribs = array())
	{
		if (!$version && $version !== false)
		{
			$version = $this->getVersion();
		}

		$file = array(
			'url' => $this->handleUri($url),
			'attribs' => $attribs,
			'version' => $version
		);

		$this->scripts[$url] = $file;

		return $this;
	}

	/**
	 * internalStyle
	 *
	 * @param string $content
	 *
	 * @return  static
	 */
	public function internalStyle($content)
	{
		$this->internalStyles[] = $content;

		return $this;
	}

	/**
	 * internalStyle
	 *
	 * @param string $content
	 *
	 * @return  static
	 */
	public function internalScript($content)
	{
		$this->internalScripts[] = $content;

		return $this;
	}

	/**
	 * renderStyles
	 *
	 * @param bool $withInternal
	 *
	 * @return string
	 */
	public function renderStyles($withInternal = false)
	{
		$html = array();

		Ioc::getApplication()->triggerEvent('onPhoenixRenderStyles', array(
			'asset' => $this,
			'withInternal' => &$withInternal,
			'html' => &$html
		));

		foreach ($this->styles as $url => $style)
		{
			$defaultAttribs = array(
				'rel' => 'stylesheet',
				'href' => $style['url']
			);

			$attribs = array_merge($defaultAttribs, $style['attribs']);

			if ($style['version'] !== false)
			{
				$attribs['href'] .= '?' . $style['version'];
			}

			$html[] = (string) new HtmlElement('link', null, $attribs);
		}

		if ($withInternal && $this->internalStyles)
		{
			$html[] = (string) new HtmlElement('style', "\n" . $this->renderInternalStyles() . "\n" . $this->indents);
		}

		return implode("\n" . $this->indents, $html);
	}

	/**
	 * renderStyles
	 *
	 * @param bool $withInternal
	 *
	 * @return string
	 */
	public function renderScripts($withInternal = false)
	{
		$html = array();

		Ioc::getApplication()->triggerEvent('onPhoenixRenderScripts', array(
			'asset' => $this,
			'withInternal' => &$withInternal,
			'html' => &$html
		));

		foreach ($this->scripts as $url => $script)
		{
			$defaultAttribs = array(
				'src' => $script['url']
			);

			$attribs = array_merge($defaultAttribs, $script['attribs']);

			if ($script['version'] !== false)
			{
				$attribs['src'] .= '?' . $script['version'];
			}

			$html[] = (string) new HtmlElement('script', null, $attribs);
		}

		if ($withInternal && $this->internalScripts)
		{
			$html[] = (string) new HtmlElement('script', "\n" . $this->renderInternalScripts() . "\n" . $this->indents);
		}

		return implode("\n" . $this->indents, $html);
	}

	/**
	 * renderInternalStyles
	 *
	 * @return  string
	 */
	public function renderInternalStyles()
	{
		return implode("\n\n", $this->internalStyles);
	}

	/**
	 * renderInternalStyles
	 *
	 * @return  string
	 */
	public function renderInternalScripts()
	{
		return implode(";\n", $this->internalScripts);
	}

	/**
	 * getVersion
	 *
	 * @return  string
	 */
	public function getVersion()
	{
		if ($this->version)
		{
			return $this->version;
		}

		$sumFile = WINDWALKER_CACHE . '/phoenix/asset/MD5SUM';

		if (!is_file($sumFile))
		{
			if (WINDWALKER_DEBUG)
			{
				return $this->version = md5(uniqid());
			}
			else
			{
				return $this->detectVersion();
			}
		}

		return $this->version = trim(file_get_contents($sumFile));
	}

	/**
	 * detectVersion
	 *
	 * @return  string
	 */
	protected function detectVersion()
	{
		static $version;

		if ($version)
		{
			return $version;
		}

		$media = Ioc::getEnvironment()->server->getServerPublicRoot() . '/' . Uri::media(Uri::RELATIVE);

		$time = '';
		$files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($media, \FilesystemIterator::FOLLOW_SYMLINKS));

		/** @var \SplFileInfo $file */
		foreach ($files as $file)
		{
			$time .= $file->getMTime();
		}

		return $version = md5(Ioc::getConfig()->get('system.secret') . $time);
	}

	/**
	 * Method to set property version
	 *
	 * @param   string $version
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setVersion($version)
	{
		$this->version = $version;

		return $this;
	}

	/**
	 * Method to get property Styles
	 *
	 * @return  array
	 */
	public function getStyles()
	{
		return $this->styles;
	}

	/**
	 * Method to set property styles
	 *
	 * @param   array $styles
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setStyles($styles)
	{
		$this->styles = $styles;

		return $this;
	}

	/**
	 * Method to get property Scripts
	 *
	 * @return  array
	 */
	public function getScripts()
	{
		return $this->scripts;
	}

	/**
	 * Method to set property scripts
	 *
	 * @param   array $scripts
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setScripts($scripts)
	{
		$this->scripts = $scripts;

		return $this;
	}

	/**
	 * Method to get property InternalStyles
	 *
	 * @return  array
	 */
	public function getInternalStyles()
	{
		return $this->internalStyles;
	}

	/**
	 * Method to set property internalStyles
	 *
	 * @param   array $internalStyles
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setInternalStyles($internalStyles)
	{
		$this->internalStyles = $internalStyles;

		return $this;
	}

	/**
	 * Method to get property InternalScripts
	 *
	 * @return  array
	 */
	public function getInternalScripts()
	{
		return $this->internalScripts;
	}

	/**
	 * Method to set property internalScripts
	 *
	 * @param   array $internalScripts
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setInternalScripts($internalScripts)
	{
		$this->internalScripts = $internalScripts;

		return $this;
	}

	/**
	 * Method to set property indents
	 *
	 * @param   string $indents
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setIndents($indents)
	{
		$this->indents = $indents;

		return $this;
	}

	/**
	 * Method to get property Indents
	 *
	 * @return  string
	 */
	public function getIndents()
	{
		return $this->indents;
	}

	/**
	 * handleUri
	 *
	 * @param   string  $uri
	 *
	 * @return  string
	 */
	protected function handleUri($uri)
	{
		// Check has .min
		$uri = Uri::addBase($uri, 'media.path');

		if (strpos($uri, 'http') === 0 || strpos($uri, '//') === 0)
		{
			return $uri;
		}

		$ext = File::getExtension($uri);
		$root = Ioc::getEnvironment()->server->getServerPublicRoot();

		if (StringHelper::endsWith($uri, '.min.' . $ext))
		{
			$assetFile = substr($uri, 0, -strlen('.min.' . $ext)) . '.' . $ext;
			$assetMinFile = $uri;
		}
		else
		{
			$assetMinFile = substr($uri, 0, -strlen('.' . $ext)) . '.min.' . $ext;
			$assetFile = $uri;
		}

		// Use uncompressed file first
		if (WINDWALKER_DEBUG)
		{
			if (is_file($root . '/' . $assetFile))
			{
				return $assetFile;
			}

			if (is_file($root . '/' . $assetMinFile))
			{
				return $assetMinFile;
			}
		}

		// Use min file first
		else
		{
			if (is_file($root . '/' . $assetMinFile))
			{
				return $assetMinFile;
			}

			if (is_file($root . '/' . $assetFile))
			{
				return $assetFile;
			}
		}

		// All file not found, fallback to default uri.
		return $uri;
	}

	/**
	 * Internal method to get a JavaScript object notation string from an array
	 *
	 * @param mixed $data
	 * @param bool  $quoteKey
	 *
	 * @return string JavaScript object notation representation of the array
	 */
	public static function getJSObject($data, $quoteKey = true)
	{
		if ($data === null)
		{
			return 'null';
		};

		$output = '';

		switch (gettype($data))
		{
			case 'boolean':
				$output .= $data ? 'true' : 'false';
				break;

			case 'float':
			case 'double':
			case 'integer':
				$output .= $data + 0;
				break;

			case 'array':
				if (!ArrayHelper::isAssociative($data))
				{
					$child = array();

					foreach ($data as $value)
					{
						$child[] = static::getJSObject($value, $quoteKey);
					}

					$output .= '[' . implode(',', $child) . ']';
					break;
				}

			case 'object':
				$array = is_object($data) ? get_object_vars($data) : $data;

				$row = array();

				foreach ($array as $key => $value)
				{
					$key = json_encode($key);

					if (!$quoteKey)
					{
						$key = substr(substr($key, 0, -1), 1);
					}

					$row[] = $key . ':' . static::getJSObject($value, $quoteKey);
				}

				$output .= '{' . implode(',', $row) . '}';
				break;

			default:  // anything else is treated as a string
				return strpos($data, '\\') === 0 ? substr($data, 1) : json_encode($data);
				break;
		}

		return $output;
	}
}
