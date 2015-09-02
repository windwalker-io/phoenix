<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Registry\Format\JsonFormat;

/**
 * The JQuery class.
 *
 * @since  {DEPLOY_VERSION}
 */
class JQueryScript extends ScriptManager
{
	/**
	 * jquery
	 *
	 * @param   boolean $noConflict
	 *
	 * @return  void
	 */
	public static function core($noConflict = false)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/jquery/jquery.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript('jQuery.noConflict();');
		}
	}

	/**
	 * ui
	 *
	 * @param array $components
	 *
	 * @return  void
	 */
	public static function ui(array $components)
	{
		static::core();
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/jquery/jquery.ui.core.min.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$allowedComponents = array(
				'draggable',
				'droppable',
				'resizable',
				'selectable',
				'sortable'
			);

			foreach ($components as $component)
			{
				if (in_array($component, $allowedComponents))
				{
					$asset->addScript(static::phoenixName() . '/js/jquery/jquery.ui.' . $component . '.min.js');
				}
			}
		}
	}

	/**
	 * colorpicker
	 *
	 * @param string $selector
	 * @param array  $options
	 *
	 * @return  void
	 */
	public static function colorPicker($selector = '.colorpicker', $options = array())
	{
		static::core();
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/jquery/jquery.minicolors.min.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$defaultOptions = array(
				'control' => 'hue',
				'position' => 'right',
				'theme' => 'bootstrap'
			);

			$options = json_encode((object) array_merge($defaultOptions, $options), JsonFormat::prettyPrint());

			$js = <<<JS
// Color picker
jQuery(document).ready(function($)
{
	$('.minicolors').each(function() {
		$(this).minicolors($options);
	});
});
JS;
			$asset->internalScript($js);
		}
	}
}
