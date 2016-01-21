<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$}\Helper;

use Windwalker\Core\View\Helper\AbstractHelper;

/**
 * The MenuHelper class.
 *
 * @since  1.0
 */
class MenuHelper extends AbstractHelper
{
	const PLURAL = 'plural';
	const SINGULAR = 'singular';

	/**
	 * active
	 *
	 * @param   string  $name
	 * @param   string  $menu
	 *
	 * @return  string
	 */
	public function active($name, $menu = 'submenu')
	{
		$view = $this->getParent()->getView();

		if ($view['app']->get('route.matched') == $view->getPackage()->getName() . ':' . $name)
		{
			return 'active';
		}

		if ($view['app']->get('route.extra.active.' . $menu) == $name)
		{
			return 'active';
		}

		return null;
	}
}
