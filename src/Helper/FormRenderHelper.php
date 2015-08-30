<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Helper;

use Windwalker\Core\Widget\BladeWidget;

/**
 * The FormRenderer class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class FormRenderHelper
{
	/**
	 * render
	 *
	 * @param array  $fields
	 * @param string $labelCols
	 * @param string $inputCols
	 * @param string $tmpl
	 *
	 * @return string
	 */
	public static function render(array $fields, $labelCols = 'col-md-2', $inputCols = 'col-md-10', $tmpl = 'phoenix.admin.form.fields')
	{
		return (new BladeWidget($tmpl))->render([
			'fields' => $fields,
			'label_cols' => $labelCols,
			'input_cols' => $inputCols
		]);
	}
}
