<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\Document;
use Windwalker\Core\View\PhpHtmlView;
use Windwalker\Data\Data;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Renderer\RendererInterface;

/**
 * The AbstractRadHtmView class.
 *
 * @since  1.0
 */
abstract class AbstractPhoenixHtmView extends PhpHtmlView
{
	/**
	 * Method to instantiate the view.
	 *
	 * @param   array             $data     The data array.
	 * @param   RendererInterface $renderer The renderer engine.
	 */
	public function __construct($data = array(), RendererInterface $renderer = null)
	{
		$renderer = $renderer ? : new BladeRenderer;

		parent::__construct($data, $renderer);
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
		Document::setTitle($title);

		return $this;
	}
}
