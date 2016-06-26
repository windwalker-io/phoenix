<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$};

use Windwalker\Core\View\RegistryView;
use Windwalker\Registry\Registry;

/**
 * The {$controller.list.name.cap$}JsonView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class {$controller.list.name.cap$}JsonView extends RegistryView
{
	/**
	 * prepareData
	 *
	 * @param Registry $registry
	 *
	 * @return  void
	 */
	protected function prepareData($registry)
	{
		$registry['items'] = $this->model->getItems();
	}
}
