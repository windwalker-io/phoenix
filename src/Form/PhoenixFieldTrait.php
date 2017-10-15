<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Form;

use Phoenix\Field\CalendarField;
use Phoenix\Field\InlineField;
use Phoenix\Field\ItemListField;
use Phoenix\Field\ModalField;
use Phoenix\Field\SqlListField;
use Phoenix\Field\SwitchField;

/**
 * The PhoenixFieldTrait class.
 *
 * @method  CalendarField calendar($name = null, $label = null)
 * @method  ItemListField itemList($name = null, $label = null)
 * @method  ModalField    modal($name = null, $label = null)
 * @method  SqlListField  sqlList($name = null, $label = null)
 * @method  SwitchField   switch($name = null, $label = null)
 * @method  InlineField   inline($name = null, $label = null)
 *
 * @since  1.1
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
