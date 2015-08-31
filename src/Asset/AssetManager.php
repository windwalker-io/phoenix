<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
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

/**
 * The AssetManager class.
 * 
 * @since  {DEPLOY_VERSION}
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

		if (strpos('http', $uri) === 0 || strpos('//', $uri) === 0)
		{
			return $uri;
		}

		$ext = File::getExtension($uri);
		$root = Ioc::getEnvironment()->server->getServerPublicRoot();

		// Add min
		if (WINDWALKER_DEBUG)
		{
			// Uri has .min and uncompressed file exists, use uncompressed file in debug mode
			if (StringHelper::endsWith($uri, '.min.' . $ext))
			{
				$assetFile = substr($uri, 0, -strlen('.min.' . $ext)) . '.' . $ext;

				if (is_file($root . '/' . $assetFile))
				{
					return $assetFile;
				}
			}
		}
		else
		{
			if (!StringHelper::endsWith($uri, '.min.' . $ext))
			{
				$assetMinFile = substr($uri, 0, -strlen('.' . $ext)) . '.min.' . $ext;

				if (is_file($root . '/' . $assetMinFile))
				{
					return $assetMinFile;
				}
			}
		}

		return $uri;
	}
}
