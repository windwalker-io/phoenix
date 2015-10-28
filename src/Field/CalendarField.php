<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\TextField;

/**
 * The CalendarField class.
 *
 * @since  1.0
 */
class CalendarField extends TextField
{
	/**
	 * prepare
	 *
	 * @param array $attrs
	 *
	 * @return  void
	 */
	public function prepare(&$attrs)
	{
		parent::prepare($attrs);
	}

	/**
	 * buildInput
	 *
	 * @param array $attrs
	 *
	 * @return  string
	 */
	public function buildInput($attrs)
	{
		$input  = parent::buildInput($attrs);
		$format = $this->get('format', 'YYYY-MM-DD HH:mm:ss');
		$id     = $this->getId();

		return WidgetHelper::render('phoenix.form.field.calendar', array(
			'id'     => $id,
			'input'  => $input,
			'attrs'  => $attrs,
			'format' => $format,
			'field'  => $this
		), WidgetHelper::ENGINE_BLADE);
	}
}
