<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$};

use Phoenix\View\GridView;

/**
 * The {$controller.list.name.cap$}HtmlView class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$controller.list.name.cap$}HtmlView extends GridView
{
	/**
	 * Property orderField.
	 *
	 * @var  string
	 */
	protected $orderColumn = '{$controller.item.name.lower$}.ordering';

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{

	}
}
