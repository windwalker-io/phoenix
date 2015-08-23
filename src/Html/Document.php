<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

use Windwalker\Core\Facade\AbstractProxyFacade;

/**
 * The Document class.
 * 
 * @since  {DEPLOY_VERSION}
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
