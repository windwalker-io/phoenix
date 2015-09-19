<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Windwalker\Core\Language\Translator;

/**
 * The ListHtmlView class.
 *
 * @since  1.0
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
		$title = $title ? : Translator::sprintf('phoenix.title.list', Translator::translate($this->package->getName() . '.' . $this->getName() . '.title'));

		return parent::setTitle($title);
	}
}
