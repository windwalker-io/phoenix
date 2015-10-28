<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Windwalker\Core\Language\Translator;
use Windwalker\Data\Data;

/**
 * The ListHtmlView class.
 *
 * @since  1.0
 */
class ListView extends AbstractPhoenixHtmView
{
	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		$title = $title ? : Translator::sprintf('phoenix.title.list', Translator::translate($this->package->getName() . '.' . $this->getName() . '.title'));

		return parent::setTitle($title);
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
		parent::prepareRender($data);

		$data->items      = $this->model->getItems();
		$data->pagination = $this->model->getPagination();
	}
}
