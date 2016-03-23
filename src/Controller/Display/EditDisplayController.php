<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Windwalker\Core\Model\Model;

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
	 * @param Model $model
	 *
	 * @return void
	 */
	protected function prepareUserState(Model $model)
	{
		parent::prepareUserState($model);

		if ($this->input->get('new') !== null)
		{
			$this->removeUserState($this->getContext('edit.data'));
		}

		$model['form.data'] = $this->getUserState($this->getContext('edit.data'));

		$this->removeUserState($this->getContext('edit.data'));
	}
}
