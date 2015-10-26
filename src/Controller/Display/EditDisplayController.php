<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

/**
 * The EditGetController class.
 *
 * @since  1.0.5
 */
class EditDisplayController extends ItemDisplayController
{
	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareUserState()
	{
		parent::prepareUserState();

		if ($this->input->get('new') !== null)
		{
			$this->removeUserState($this->getContext('edit.data'));
		}

		$this->model['form.data'] = $this->getUserState($this->getContext('edit.data'));
	}
}
