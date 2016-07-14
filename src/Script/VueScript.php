<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Ioc;

/**
 * The VueScript class.
 *
 * @since  {DEPLOY_VERSION}
 */
class VueScript extends AbstractPhoenixScript
{
	/**
	 * Vue core.
	 *
	 * @return  void
	 */
	public static function core()
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/vue/vue.min.js');
		}
	}

	/**
	 * Vue Resource.
	 *
	 * @see  Configuration           https://github.com/vuejs/vue-resource/blob/master/docs/config.md
	 * @see  HTTP Requests/Response  https://github.com/vuejs/vue-resource/blob/master/docs/http.md
	 * @see  Creating Resources      https://github.com/vuejs/vue-resource/blob/master/docs/resource.md
	 * @see  Code Recipes            https://github.com/vuejs/vue-resource/blob/master/docs/recipes.md
	 *
	 * @param array $options
	 * @param array $headers
	 */
	public static function resource(array $options = [], array $headers = [])
	{
		if (!static::inited(__METHOD__))
		{
			static::core();

			static::addJS(static::phoenixName() . '/js/vue/vue-resource.min.js');

			$defaultOptions = [
				'root' => Ioc::getUriData()->path,
			];

			$options = static::getJSObject($defaultOptions, $options);

			$defaultHealders = [
				'common' => [
					'X-Csrf-Token' => CsrfProtection::getFormToken()
				]
			];

			$headers = static::mergeOptions($defaultHealders, $headers);
			$headers = array_intersect_key($headers, array_flip(['common', 'custom', 'delete', 'patch', 'post', 'put']));

			$js[] = "// Init Vue-resource http settings.";
			$js[] = "Vue.http.options = Object.assign({}, Vue.http.options, $options);";

			foreach ($headers as $key => $headerLines)
			{
				if (count($headerLines))
				{
					$js[] = "Vue.http.headers.$key = Object.assign({}, Vue.http.headers.$key, " . static::getJSObject($headerLines) . ");";
				}
			}

			static::internalJS(implode("\n", $js));
		}
	}

	/**
	 * Vue Router.
	 *
	 * @see  http://router.vuejs.org/en/index.html
	 *
	 * @return  void
	 */
	public static function router()
	{
		if (!static::inited(__METHOD__))
		{
			static::core();

			static::addJS(static::phoenixName() . '/js/vue/vue-router.min.js');
		}
	}

	/**
	 * Add VueStrap.js
	 *
	 * @see  https://yuche.github.io/vue-strap/
	 *
	 * @return  void
	 */
	public static function strap()
	{
		if (!static::inited(__METHOD__))
		{
			static::core();
			BootstrapScript::css();
			BootstrapScript::script();

			static::addJS(static::phoenixName() . '/js/vue/vue-strap.min.js');
		}
	}

	/**
	 * Vuex storage library.
	 *
	 * @see  http://vuex.vuejs.org/en/index.html
	 *
	 * @return  void
	 */
	public static function vuex(array $stores = [])
	{
		if (!static::inited(__METHOD__))
		{
			static::core();

			static::addJS(static::phoenixName() . '/js/vue/vuex.min.js');
		}

		if ($stores)
		{
			foreach ($stores as $name => $store)
			{
				static::store($name, $store);
			}
		}
	}

	/**
	 * store
	 *
	 * @param string $name
	 * @param array  $store
	 *
	 * @return  void
	 */
	public static function store($name, array $store = [])
	{
		static::vuex();

		if (!static::inited(__METHOD__, $name))
		{
			static::internalJS("const $name = " . static::getJSObject($store) . ';');
		}
	}
}
