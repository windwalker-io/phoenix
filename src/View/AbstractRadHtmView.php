<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Windwalker\Core\View\HtmlView;
use Windwalker\Renderer\BladeRenderer;
use Windwalker\Renderer\RendererInterface;

/**
 * The AbstractRadHtmView class.
 *
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractRadHtmView extends HtmlView
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
}
