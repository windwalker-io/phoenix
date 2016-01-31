<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Dom\HtmlElement;

/**
 * The HtmlHeaderManager class.
 *
 * @since  1.0.13
 */
class HtmlHeaderManager
{
	/**
	 * Property title.
	 *
	 * @var  string
	 */
	protected $title;

	/**
	 * Property siteName.
	 *
	 * @var  string
	 */
	protected $siteName;

	/**
	 * Property favicon.
	 *
	 * @var  string
	 */
	protected $favicon;

	/**
	 * Property metadata.
	 *
	 * @var  Metadata
	 */
	protected $metadata;

	/**
	 * Property customTags.
	 *
	 * @var  array
	 */
	protected $customTags = array();

	/**
	 * Property indents.
	 *
	 * @var  string
	 */
	protected $indents = '    ';

	/**
	 * HtmlHeaderManager constructor.
	 *
	 * @param Metadata $metadata
	 */
	public function __construct(Metadata $metadata = null)
	{
		$this->metadata = $metadata ? : new Metadata;
	}

	/**
	 * Method to get property Title
	 *
	 * @param string $separator
	 *
	 * @return string
	 */
	public function getPageTitle($separator = ' | ')
	{
		$title = $this->title;

		if ($title && $this->siteName)
		{
			$title = $title . $separator . $this->siteName;
		}
		elseif (!$title)
		{
			$title = $this->siteName;
		}

		return $title;
	}

	/**
	 * getTitle
	 *
	 * @return  string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Method to set property title
	 *
	 * @param   string $title
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Method to get property Favicon
	 *
	 * @return  string
	 */
	public function getFavicon()
	{
		return $this->favicon;
	}

	/**
	 * Method to set property favicon
	 *
	 * @param   string $favicon
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setFavicon($favicon)
	{
		$this->favicon = $favicon;

		return $this;
	}

	/**
	 * addCustomTag
	 *
	 * @param string $tag
	 * @param string $content
	 * @param array  $attribs
	 *
	 * @return  static
	 */
	public function addCustomTag($tag, $content = null, $attribs = array())
	{
		if (!$tag instanceof HtmlElement)
		{
			$tag = new HtmlElement($tag, $content, $attribs);
		}

		$this->customTags[] = $tag;

		return $this;
	}

	/**
	 * Method to get property CustomTags
	 *
	 * @return  array
	 */
	public function getCustomTags()
	{
		return $this->customTags;
	}

	/**
	 * Method to set property customTags
	 *
	 * @param   array $customTags
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setCustomTags($customTags)
	{
		$this->customTags = $customTags;

		return $this;
	}

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
		$this->metadata->addMetadata($name, $content, $replace);

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
		$this->metadata->addOpenGraph($type, $content, $replace);

		return $this;
	}

	/**
	 * renderFavicon
	 *
	 * @return  string
	 */
	public function renderFavicon()
	{
		if (!$this->favicon)
		{
			return null;
		}

		return (string) new HtmlElement('link', null, array(
			'rel' => 'shortcut icon',
			'type' => 'image/x-icon',
			'href' => $this->favicon
		));
	}

	/**
	 * renderTitle
	 *
	 * @return  string
	 */
	public function renderTitle()
	{
		return (string) new HtmlElement('title', $this->getTitle());
	}

	/**
	 * renderMetadata
	 *
	 * @return  string
	 */
	public function renderMetadata()
	{
		$html = array();

		foreach ($this->metadata->getMetadata() as $metadata)
		{
			foreach ($metadata as $item)
			{
				$html[] = (string) $item;
			}
		}

		foreach ($this->metadata->getOpenGraphs() as $opengraphs)
		{
			foreach ($opengraphs as $item)
			{
				$html[] = (string) $item;
			}
		}

		return implode("\n" . $this->indents, $html);
	}

	/**
	 * remnderCustomTags
	 *
	 * @return  string
	 */
	public function renderCustomTags()
	{
		$html = array();

		foreach ($this->customTags as $tag)
		{
			$html[] = (string) $tag;
		}

		return implode("\n" . $this->indents, $html);
	}

	/**
	 * Method to get property SiteName
	 *
	 * @return  string
	 */
	public function getSiteName()
	{
		return $this->siteName;
	}

	/**
	 * Method to set property siteName
	 *
	 * @param   string $siteName
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setSiteName($siteName)
	{
		$this->siteName = $siteName;

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
	 * Method to get property Metadata
	 *
	 * @return  Metadata
	 */
	public function getMetadata()
	{
		return $this->metadata;
	}

	/**
	 * Method to set property metadata
	 *
	 * @param   Metadata $metadata
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setMetadata(Metadata $metadata)
	{
		$this->metadata = $metadata;

		return $this;
	}
}
