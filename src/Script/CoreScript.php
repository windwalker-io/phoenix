<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Utilities\ArrayHelper;

/**
 * The CoreScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @since  1.0
 */
abstract class CoreScript extends AbstractScriptManager
{
	/**
	 * requireJS
	 *
	 * @return  void
	 */
	public static function requireJS()
	{
		if (!static::inited(__METHOD__))
		{
			static::getAsset()->addScript(static::phoenixName() . '/js/core/require.min.js');
		}
	}

	/**
	 * underscore
	 *
	 * @param boolean $noConflict
	 *
	 * @return  void
	 */
	public static function underscore($noConflict = true)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/core/underscore.min.js');

			$js = <<<JS
_.templateSettings = {
	evaluate: /\{\%(.+?)\%\}/g,
	interpolate: /\{\!\!(.+?)\!\!\}/g,
	escape: /\{\{(.+?)\}\}/g
};
JS;

			$asset->internalScript($js);
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript('var underscore = _.noConflict();');
		}
	}

	/**
	 * underscoreString
	 *
	 * @param bool $noConflict
	 *
	 * @return  void
	 */
	public static function underscoreString($noConflict = true)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/core/underscore.string.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$js = <<<JS
(function(s) {
	var us = function(underscore)
	{
		underscore.string = underscore.string || s;
	};
	us(window._ || (window._ = {}));
	us(window.underscore || (window.underscore = {}));
})(s);
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * backbone
	 *
	 * @param bool  $noConflict
	 * @param array $options
	 *
	 * @return  void
	 */
	public static function backbone($noConflict = false, $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			JQueryScript::core(ArrayHelper::getValue($options, 'jquery_no_conflict', false));
			static::underscore(ArrayHelper::getValue($options, 'jquery_no_conflict', true));

			$asset->addScript(static::phoenixName() . '/js/core/backbone.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript(';var backbone = Backbone.noConflict();');
		}
	}
}
