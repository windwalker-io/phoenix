<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$};

use Phoenix\Script\BootstrapScript;
use Phoenix\Script\CoreScript;
use Phoenix\Script\JQueryScript;
use Phoenix\Script\PhoenixScript;
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
		$this->prepareScripts();
	}

	/**
	 * prepareDocument
	 *
	 * @return  void
	 */
	protected function prepareScripts()
	{
		BootstrapScript::css();
		BootstrapScript::script();
		PhoenixScript::core();
		PhoenixScript::grid();
		PhoenixScript::multiSelect(100);
		PhoenixScript::chosen();
		BootstrapScript::checkbox();
		BootstrapScript::tooltip();
	}
}
