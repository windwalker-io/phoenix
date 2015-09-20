<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Phoenix\Script\BootstrapScript;
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
		$attrs['class'] .= ' hasCalendar';
		$format = $this->get('format', 'YYYY-MM-DD HH:mm:ss');
		$input =  parent::buildInput($attrs);
		$id = $this->getId();

		BootstrapScript::calendar('#' . $id . '-wrapper', $format);

		$html = <<<HTML
<div id="$id-wrapper" class="input-group date">
$input
<span class="input-group-addon">
	<span class="glyphicon glyphicon-calendar fa fa-calendar"></span>
</span>
</div>
HTML;

		return $html;
	}
}
