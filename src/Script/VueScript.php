<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Ioc;
use Windwalker\String\StringNormalise;

/**
 * The VueScript class.
 *
 * @since  1.1
 */
abstract class VueScript extends AbstractPhoenixScript
{
    /**
     * Vue core.
     *
     * @return  void
     */
    public static function core()
    {
        if (!static::inited(__METHOD__)) {
            static::addJS(static::phoenixName() . '/js/vue/vue.min.js');
        }
    }

    /**
     * instance
     *
     * @param string $selector
     * @param array  $data
     * @param array  $properties
     *
     * @return  void
     */
    public static function instance($selector, array $data = [], array $properties = [])
    {
        static::core();
        JQueryScript::core();

        $var = lcfirst(StringNormalise::toCamelCase(trim($selector, '.#[]')));

        $instance = [
            'el' => $selector,
            'data' => $data,
        ];

        $instance = static::getJSObject($instance, $properties);

        static::internalJS(<<<JS
jQuery(function($) {
    window.vueInstances = window.vueInstances || {};
    window.vueInstances.$var = new Vue($instance);
});
JS
        );
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
        if (!static::inited(__METHOD__)) {
            static::core();

            static::addJS(static::phoenixName() . '/js/vue/vue-resource.min.js');

            $defaultOptions = [
                'root' => Ioc::getUriData()->path,
            ];

            $options = static::getJSObject($defaultOptions, $options);

            $defaultHealders = [
                'common' => [
                    'X-CSRF-Token' => CsrfProtection::getFormToken(),
                ],
            ];

            $headers = static::mergeOptions($defaultHealders, $headers);
            $headers = array_intersect_key($headers,
                array_flip(['common', 'custom', 'delete', 'patch', 'post', 'put']));

            $js[] = "// Init Vue-resource http settings.";
            $js[] = "Vue.http.options = Object.assign({}, Vue.http.options, $options);";

            foreach ($headers as $key => $headerLines) {
                if (count($headerLines)) {
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
        if (!static::inited(__METHOD__)) {
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
        if (!static::inited(__METHOD__)) {
            static::core();
            BootstrapScript::script();

            static::addJS(static::phoenixName() . '/js/vue/vue-strap.min.js');
        }
    }

    /**
     * Vuex storage library.
     *
     * @see  http://vuex.vuejs.org/en/index.html
     *
     * @param array $stores
     *
     * @return void
     */
    public static function vuex(array $stores = [])
    {
        if (!static::inited(__METHOD__)) {
            static::core();

            static::addJS(static::phoenixName() . '/js/vue/vuex.min.js');
        }

        if ($stores) {
            foreach ($stores as $name => $store) {
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
     *
     * @deprecated  Vue 2.0 must use new pattern.
     */
    public static function store($name, array $store = [])
    {
        static::vuex();

        if (!static::inited(__METHOD__, $name)) {
            $state = static::getJSObject(['state' => $store]);

            $js = <<<JS
var $name = new Vuex.Store($state);
JS;

            static::internalJS($js);
        }
    }

    /**
     * Animate.css for Vue.js 2.0.
     *
     * @see  https://github.com/asika32764/vue2-animate
     *
     * @return  void
     */
    public static function animate()
    {
        if (!static::inited(__METHOD__)) {
            static::core();

            static::addCSS(static::phoenixName() . '/css/vue/vue2-animate.min.css');
        }
    }

    /**
     * addVModelToFields
     *
     * @param string          $prefix
     * @param AbstractField[] $fields
     * @param array           $maps
     *
     * @return  AbstractField[]
     */
    public static function addVModelToFields($prefix, $fields, array $maps = [])
    {
        /** @var AbstractField $field */
        foreach ($fields as $field) {
            $name = $field->getName(true);

            if (isset($maps[$name])) {
                if ($maps[$name] === false) {
                    continue;
                }

                $name = $maps[$name];
            }

            $field->attr('v-model', $prefix . '.' . $name);
        }

        return $fields;
    }
}
