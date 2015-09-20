<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Dom\HtmlElement;

/**
 * The DocumentManager class.
 * 
 * @since  1.0
 */
class DocumentManager
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
	 * Property openGraphs.
	 *
	 * @var  array
	 */
	protected $metadata = array();

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
	 * @param   string  $name
	 * @param   string  $content
	 *
	 * @return  static
	 */
	public function addMetadata($name, $content)
	{
		$this->metadata[] = new HtmlElement('meta', null, array(
			'name' => $name,
			'content' => $content
		));

		return $this;
	}

	/**
	 * addOpenGraph
	 *
	 * @param   string  $type
	 * @param   string  $content
	 *
	 * @return  static
	 */
	public function addOpenGraph($type, $content)
	{
		$this->metadata[] = new HtmlElement('meta', null, array(
			'property' => $type,
			'content' => $content
		));

		return $this;
	}

	/**
	 * Method to get property metadata
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
	 * @param   array $openGraphs
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setMetadata($openGraphs)
	{
		$this->metadata = $openGraphs;

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

		foreach ($this->metadata as $metadata)
		{
			$html[] = (string) $metadata;
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
}
