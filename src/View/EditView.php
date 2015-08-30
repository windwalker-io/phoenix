<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

/**
 * The EditView class.
 *
 * @since  {DEPLOY_VERSION}
 */
class EditView extends ItemView
{
	protected function prepareRender($data)
	{
		$data->item = $this->model->getItem();
		$data->form = $this->model->getForm('edit', 'item', true);
	}
}
