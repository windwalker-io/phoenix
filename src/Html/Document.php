<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The Document class.
 *
 * @see  \Phoenix\Html\DocumentManager
 *
 * @method  static  string           getPageTitle($separator = ' | ')
 * @method  static  string           getTitle()
 * @method  static  DocumentManager  setTitle($title)
 * @method  static  string           getFavicon()
 * @method  static  DocumentManager  etFavicon($favicon)
 * @method  static  DocumentManager  addCustomTag($tag, $content = null, $attribs = array())
 * @method  static  string           getCustomTags()
 * @method  static  DocumentManager  setCustomTags($customTags)
 * @method  static  DocumentManager  addMetadata($name, $content)
 * @method  static  DocumentManager  removeMetadata($name)
 * @method  static  DocumentManager  addOpenGraph($type, $content)
 * @method  static  DocumentManager  removeOpenGraph($type)
 * @method  static  string           getMetadata()
 * @method  static  DocumentManager  setMetadata($metadata)
 * @method  static  DocumentManager  getOpenGraphs()
 * @method  static  DocumentManager  setOpenGraphs($openGraphs)
 * @method  static  string           renderFavicon()
 * @method  static  string           renderTitle()
 * @method  static  string           renderMetadata()
 * @method  static  string           renderCustomTags()
 * @method  static  string           getSiteName()
 * @method  static  DocumentManager  setSiteName($siteName)
 * @method  static  string           getIndents()
 * @method  static  DocumentManager  setIndents($indents)
 *
 * @since  1.0
 */
abstract class Document extends AbstractProxyFacade
{
	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'phoenix.document';
}
