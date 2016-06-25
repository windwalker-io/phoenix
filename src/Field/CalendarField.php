<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Ioc;
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
		// Convert timezone
		$from = $this->get('from', 'UTC');
		$to   = $this->get('to', Ioc::getConfig()->get('system.timezone', 'UTC'));

		$attrs['value'] = DateTime::convert($attrs['value'], $from, $to);

		$input  = parent::buildInput($attrs);
		$format = $this->get('format', 'YYYY-MM-DD HH:mm:ss');
		$id     = $this->getId();

		return WidgetHelper::render('phoenix.form.field.calendar', array(
			'id'     => $id,
			'input'  => $input,
			'attrs'  => $attrs,
			'format' => $format,
			'field'  => $this
		), WidgetHelper::EDGE);
	}
}
