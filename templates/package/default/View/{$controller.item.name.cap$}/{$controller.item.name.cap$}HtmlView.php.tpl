<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$};

use Phoenix\Html\Document;
use Phoenix\Script\BootstrapScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\View\EditView;

/**
 * The {$controller.item.name.cap$}HtmlView class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class {$controller.item.name.cap$}HtmlView extends EditView
{
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
		PhoenixScript::chosen();
		PhoenixScript::core();
		// PhoenixScript::formValidation();
		BootstrapScript::checkbox();
		BootstrapScript::buttonRadio();
		BootstrapScript::tooltip();
	}
}
