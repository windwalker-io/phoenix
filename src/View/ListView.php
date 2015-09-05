<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Windwalker\Core\Language\Translator;

/**
 * The ListHtmlView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ListView extends AbstractRadHtmView
{
	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		$title = $title ? : Translator::sprintf('phoenix.title.list', Translator::translate($this->package->getName() . '.' . $this->getName()));

		return parent::setTitle($title);
	}
}
