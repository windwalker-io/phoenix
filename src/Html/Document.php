<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Html;

/**
 * The Document class.
 *
 * @see  \Phoenix\Html\DocumentManager
 *
 * @since  1.0
 *
 * @deprecated  Use HtmlHeader instead.
 */
abstract class Document extends HtmlHeader
{
	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'phoenix.document';
}
