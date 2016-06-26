<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\HtmlHeader;
use Windwalker\Core\View\HtmlView;
use Windwalker\Data\Data;

/**
 * The AbstractRadHtmView class.
 *
 * @since  1.0
 */
abstract class AbstractPhoenixHtmView extends HtmlView
{
	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix;

	/**
	 * prepareRender
	 *
	 * @param   Data $data
	 *
	 * @return  void
	 */
	protected function prepareRender($data)
	{
		$data->state = $data->state ? : $this->model->getState();

		$this->langPrefix = $this->langPrefix ? : $this->getPackage()->getName() . '.';

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
