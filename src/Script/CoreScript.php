<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\Html\HtmlHeader;
use WhichBrowser\Parser;
use Windwalker\Core\Browser\WhichBrowserFactory;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Environment\Browser\Browser;
use Windwalker\Ioc;
use Windwalker\Utilities\Arr;

/**
 * The CoreScript class.
 *
 * @see    AbstractPhoenixScript
 *
 * @since  1.0
 */
abstract class CoreScript extends AbstractPhoenixScript
{
	/**
	 * requireJS
	 *
	 * @return  void
	 */
	public static function requireJS($uri = null)
	{
		if (!static::inited(__METHOD__))
		{
			static::getAsset()->addScript(static::phoenixName() . '/js/core/require.min.js');
		}

		if (!static::inited(__METHOD__, get_defined_vars()) && $uri)
		{
			static::internalJS("require(['$uri']);");
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
			$onload = '_.erbTemplate = _.templateSettings; _.bladeTemplate = _.templateSettings = { evaluate: /\{\%(.+?)\%\}/g, interpolate: /\{\!\!(.+?)\!\!\}/g, escape: /\{\{(.+?)\}\}/g };';

			$asset->addScript(static::phoenixName() . '/js/core/underscore.min.js', null, ['onload' => $onload]);
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
	public static function backbone($noConflict = false, $options = [])
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			JQueryScript::core(Arr::get($options, 'jquery_no_conflict', false));
			static::underscore(Arr::get($options, 'jquery_no_conflict', true));

			$asset->addScript(static::phoenixName() . '/js/core/backbone.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript(';var backbone = Backbone.noConflict();');
		}
	}

	/**
	 * simpleUri
	 *
	 * @param bool $noConflict
	 *
	 * @return  void
	 */
	public static function simpleUri($noConflict = false)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/core/simple-uri.min.js');
		}

		if (!static::inited(__METHOD__, (bool) $noConflict) && $noConflict)
		{
			$asset->internalScript(';var SimpleURI = URI.noConflict();');
		}
	}

	/**
	 * moment
	 *
	 * @param bool   $timezone
	 * @param string $locale
	 *
	 * @return void
	 */
	public static function moment($timezone = false, $locale = 'en-gb')
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/datetime/moment.min.js');

			$locale = $locale ? strtolower($locale) : strtolower(Translator::getLocale());

			if (in_array($locale, ['en-gb', 'zh-tw', 'zh-cn', 'ja-jp', 'ko-kr'], true))
			{
				self::addJS(static::phoenixName() . '/js/datetime/locale/' . $locale . '.js');
			}
		}

		if (!static::inited(__METHOD__) && $timezone)
		{
			static::addJS(static::phoenixName() . '/js/datetime/moment-timezone.min.js');
		}
	}

	/**
	 * csrfToken
	 *
	 * @param string $token
	 *
	 * @return  void
	 */
	public static function csrfToken($token = null)
	{
		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			// Inject Token to meta
			HtmlHeader::addMetadata('csrf-token', $token ?: CsrfProtection::getFormToken());
		}
	}

	/**
	 * silicone
	 *
	 * @return  void
	 */
	public static function silicone()
	{
		if (!static::inited(__METHOD__))
		{
			static::addCSS(static::phoenixName() . '/css/silicone/silicone.min.css');
		}
	}

	/**
	 * sprintf
	 *
	 * @return  void
	 *
	 * @since  1.3
	 */
	public static function sprintf()
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/core/sprintf.min.js');
		}
	}

	/**
	 * ivia
	 *
	 * @return  void
	 *
	 * @since  1.3.3
	 */
	public static function ivia()
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/ivia/ivia.min.js');
		}
	}
}
