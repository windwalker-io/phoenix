<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Ioc;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\TextField;

/**
 * The CalendarField class.
 *
 * @method $this from(string $value)
 * @method $this to(string $value)
 * @method $this format(string $value)
 * @method $this calendarOptions(array $options)
 * @method $this layout(string $value)
 *
 * @since  1.0
 */
class CalendarField extends TextField
{
	/**
	 * @const string
	 */
	const EMPTY_DATETIME = '0000-00-00 00:00:00';

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
		$nullDate = Chronos::getNullDate();

		if ($attrs['value'] && !in_array($attrs['value'], [static::EMPTY_DATETIME, $nullDate], true))
		{
			$attrs['value'] = Chronos::convert($attrs['value'], $from, $to);
		}
		else
		{
			$attrs['value'] = null;
		}

		$input  = parent::buildInput($attrs);
		$format = $this->get('format', 'YYYY-MM-DD HH:mm:ss');
		$id     = $this->getId();

		return WidgetHelper::render($this->get('layout', 'phoenix.form.field.calendar'), [
			'id'     => $id,
			'input'  => $input,
			'attrs'  => $attrs,
			'format' => $format,
			'field'  => $this,
			'options' => (array) $this->get('options')
		], WidgetHelper::EDGE);
	}

	/**
	 * getAccessors
	 *
	 * @return  array
	 *
	 * @since   3.1.2
	 */
	protected function getAccessors()
	{
		return array_merge(parent::getAccessors(), [
			'from',
			'to',
			'format',
			'calendarOptions' => 'options',
			'layout',
		]);
	}
}
