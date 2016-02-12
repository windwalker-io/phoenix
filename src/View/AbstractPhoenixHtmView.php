<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\HtmlHeader;
use Windwalker\Core\View\BladeHtmlView;
use Windwalker\Core\View\PhpHtmlView;
use Windwalker\Data\Data;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Renderer\RendererInterface;

/**
 * The AbstractRadHtmView class.
 *
 * @since  1.0
 */
abstract class AbstractPhoenixHtmView extends BladeHtmlView
{
	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix;

	/**
	 * Class init.
	 *
	 * @param array         $data
	 * @param BladeRenderer $renderer
	 */
	public function __construct(array $data = null, BladeRenderer $renderer = null)
	{
		parent::__construct($data, $renderer);

		$this->langPrefix = $this->langPrefix ? : $this->getPackage()->getName();
	}

	/**
	 * prepareRender
	 *
	 * @param   Data $data
	 *
	 * @return  void
	 */
	protected function prepareRender($data)
	{
		$this->setTitle();
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
		HtmlHeader::setTitle($title);

		return $this;
	}
}
