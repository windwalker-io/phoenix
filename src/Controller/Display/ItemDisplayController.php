<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class ItemDisplayController extends DisplayController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::SINGULAR;

	/**
	 * Property pkName.
	 *
	 * @var  string
	 */
	protected $pkName = 'id';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareUserState()
	{
		$this->model['item.pk'] = $this->input->get($this->pkName);
	}
}
