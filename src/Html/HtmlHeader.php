<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The HtmlHeader class.
 *
 * @see  \Phoenix\Html\HtmlHeaderManager
 *
 * @method  static  string             getPageTitle($separator = ' | ')
 * @method  static  string             getTitle()
 * @method  static  HtmlHeaderManager  setTitle($title)
 * @method  static  string             getFavicon()
 * @method  static  HtmlHeaderManager  etFavicon($favicon)
 * @method  static  HtmlHeaderManager  addCustomTag($tag, $content = null, $attribs = array())
 * @method  static  string             getCustomTags()
 * @method  static  HtmlHeaderManager  setCustomTags($customTags)
 * @method  static  HtmlHeaderManager  addMetadata($name, $content, $replace = false)
 * @method  static  HtmlHeaderManager  addOpenGraph($type, $content, $replace = false)
 * @method  static  string             renderFavicon()
 * @method  static  string             renderTitle()
 * @method  static  string             renderMetadata()
 * @method  static  string             renderCustomTags()
 * @method  static  string             getSiteName()
 * @method  static  HtmlHeaderManager  setSiteName($siteName)
 * @method  static  string             getIndents()
 * @method  static  HtmlHeaderManager  setIndents($indents)
 * @method  static  HtmlHeaderManager  setMetadata(Metadata $indents)
 * @method  static  Metadata           getMetadata()
 *
 * @since  1.0.13
 */
abstract class HtmlHeader extends AbstractProxyFacade
{
	const REPLACE = true;

	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'phoenix.document';
}
