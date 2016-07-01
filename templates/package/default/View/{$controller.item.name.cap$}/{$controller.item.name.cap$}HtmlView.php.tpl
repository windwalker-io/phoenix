<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace {$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$};

use Phoenix\Script\BootstrapScript;
use Phoenix\Script\PhoenixScript;
use Phoenix\View\EditView;
use Phoenix\View\ItemView;
use Windwalker\Core\Renderer\RendererHelper;
use Windwalker\Core\Utilities\Classes\DocblockHelper;

/**
 * The {$controller.item.name.cap$}HtmlView class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}HtmlView extends EditView
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = '{$controller.item.name.lower$}';

	/**
	 * Property formDefinition.
	 *
	 * @var  string
	 */
	protected $formDefinition = 'edit';

	/**
	 * Property formControl.
	 *
	 * @var  string
	 */
	protected $formControl = 'item';

	/**
	 * Property formLoadData.
	 *
	 * @var  boolean
	 */
	protected $formLoadData = true;

	/**
	 * prepareData
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @see  ItemView
	 * ------------------------------------------------------
	 * @var  $data->state  \Windwalker\Registry\Registry
	 * @var  $data->item   \{$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record
	 *
	 * @see  EditView
	 * ------------------------------------------------------
	 * @var  $data->form   \Windwalker\Form\Form
	 *
	 * @return  void
	 */
	protected function prepareData($data)
	{
		$this->package->app->set('sidebar.hide', true);

		parent::prepareData($data);

		$this->prepareScripts();
	}

	/**
	 * prepareDocument
	 *
	 * @return  void
	 */
	protected function prepareScripts()
	{
		PhoenixScript::core();
		PhoenixScript::chosen();
		PhoenixScript::formValidation();
		BootstrapScript::checkbox(BootstrapScript::GLYPHICONS);
		BootstrapScript::buttonRadio();
		BootstrapScript::tooltip();
	}

	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		return parent::setTitle($title);
	}
}
