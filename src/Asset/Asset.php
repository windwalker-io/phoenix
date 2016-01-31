<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Asset;

use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The Asset class.
 *
 * @see AssetManager
 *
 * @method  static  AssetManager  addStyle()                addStyle($url, $version = null, $attribs = array())
 * @method  static  AssetManager  addScript()               addScript($url, $version = null, $attribs = array())
 * @method  static  AssetManager  internalStyle()           internalStyle($content)
 * @method  static  AssetManager  internalScript()          internalScript($content)
 * @method  static  string        renderStyles()            renderStyles($withInternal = false)
 * @method  static  string        renderScripts()           renderScripts($withInternal = false)
 * @method  static  string        renderInternalStyles()    renderInternalStyles()
 * @method  static  string        renderInternalScripts()   renderInternalScripts()
 * @method  static  string        getVersion()              getVersion()
 * @method  static  AssetManager  setIndents()              setIndents(string $indents)
 * @method  static  string        getIndents()              getIndents()
 * @method  static  string        getJSObject()             getJSObject(array $array)
 *
 * @since  1.0
 */
abstract class Asset extends AbstractProxyFacade
{
	/**
	 * Property key.
	 *
	 * @var  string
	 */
	protected static $_key = 'phoenix.asset';
}
