<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Form;

use Phoenix\Field\CalendarField;
use Phoenix\Field\ItemListField;
use Phoenix\Field\ModalField;
use Phoenix\Field\SqlListField;

/**
 * The PhoenixFieldTrait class.
 *
 * @method  CalendarField calendar($name = null, $label = null)
 * @method  ItemListField itemList($name = null, $label = null)
 * @method  ModalField    modal($name = null, $label = null)
 * @method  SqlListField  sqlList($name = null, $label = null)
 *
 * @since  {DEPLOY_VERSION}
 */
trait PhoenixFieldTrait
{
	/**
	 * bootPhoenixFieldTrait
	 *
	 * @return  void
	 */
	public function bootPhoenixFieldTrait()
	{
		$this->addNamespace('Phoenix\Field');
	}
}
