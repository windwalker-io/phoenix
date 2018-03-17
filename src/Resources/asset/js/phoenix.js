'use strict';

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function ($) {
  var PhoenixCore = function () {
    _createClass(PhoenixCore, null, [{
      key: 'defaultOptions',

      /**
       * Default options.
       * @returns {Object}
       */
      get: function get() {
        return {};
      }
    }]);

    function PhoenixCore() {
      var _this = this;

      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

      _classCallCheck(this, PhoenixCore);

      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this._listeners = {};
      this.waits = [];

      // Wait dom ready
      this.wait(function (resolve) {
        $(function () {
          return resolve();
        });
      });

      // Ready
      $(function () {
        _this.completed().then(function () {
          return _this.trigger('loaded');
        });
      });
    }

    _createClass(PhoenixCore, [{
      key: 'use',
      value: function use(plugin) {
        var _this2 = this;

        if (Array.isArray(plugin)) {
          plugin.forEach(function (p) {
            return _this2.use(p);
          });
          return this;
        }

        if (plugin.is === undefined) {
          throw new Error('Plugin: ' + plugin.name + ' must instance of : ' + PhoenixPlugin.name);
        }

        var instance = plugin.install(this);
        instance.boot(this);

        this.trigger('plugin.installed', instance);

        return this;
      }
    }, {
      key: 'detach',
      value: function detach(plugin) {
        if (!plugin instanceof PhoenixPlugin) {
          throw new Error('Plugin must instance of : ' + PhoenixPlugin.name);
        }

        plugin.uninstall(this);

        this.trigger('plugin.uninstalled', plugin);

        return this;
      }
    }, {
      key: 'on',
      value: function on(event, handler) {
        if (this._listeners[event] === undefined) {
          this._listeners[event] = [];
        }

        this._listeners[event].push(handler);

        return this;
      }
    }, {
      key: 'off',
      value: function off(event) {
        delete this._listeners[event];

        return this;
      }
    }, {
      key: 'trigger',
      value: function trigger(event, args) {
        var r = [];
        this.listeners(event).forEach(function (listener) {
          r.push(listener(args));
        });

        return r;
      }
    }, {
      key: 'listeners',
      value: function listeners(event) {
        return this._listeners[event] === undefined ? [] : this._listeners[event];
      }
    }, {
      key: 'data',
      value: function data(name, value) {
        if (value === undefined) {
          return $(document).data(name);
        }

        $(document).data(name, value);

        return this;
      }
    }, {
      key: 'removeData',
      value: function removeData(name) {
        $(document).removeData(name);

        return this;
      }
    }, {
      key: 'uri',
      value: function uri(type) {
        return this.data('phoenix.uri')[type];
      }
    }, {
      key: 'asset',
      value: function asset(type) {
        return this.uri('asset')[type];
      }
    }, {
      key: 'wait',
      value: function wait(callback) {
        var d = $.Deferred();

        this.waits.push(d);

        callback(function () {
          return d.resolve();
        });

        return d;
      }
    }, {
      key: 'completed',
      value: function completed() {
        var promise = $.when.apply($, _toConsumableArray(this.waits));

        this.waits = [];

        return promise;
      }
    }, {
      key: 'plugin',
      value: function plugin(name, _plugin) {
        var self = this;
        $.fn[name] = function () {
          if (!this.data('phoenix.' + name)) {
            for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
              args[_key] = arguments[_key];
            }

            var _instance = new (Function.prototype.bind.apply(_plugin, [null].concat([this], args)))();
            this.data('phoenix.' + name, _instance);
            self.trigger('jquery.plugin.created', { name: name, ele: this, instance: _instance });
          }

          var instance = this.data('phoenix.' + name);

          self.trigger('jquery.plugin.get', { name: name, ele: this, instance: instance });

          return instance;
        };

        return this;
      }
    }]);

    return PhoenixCore;
  }();

  window.PhoenixCore = PhoenixCore;
})(jQuery);

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function ($) {
  var PhoenixPlugin = function () {
    _createClass(PhoenixPlugin, [{
      key: 'options',
      get: function get() {
        return this.phoenix.options[this.constructor.is.toLowerCase()];
      }
    }], [{
      key: 'install',
      value: function install(phoenix) {
        var self = new this();

        this.createProxies(phoenix, self);
        return self;
      }
    }, {
      key: 'uninstall',
      value: function uninstall(phoenix) {
        var self = new this(phoenix);

        this.resetProxies(phoenix, self);
      }
    }, {
      key: 'is',
      get: function get() {
        throw new Error('Please add "is" property to Phoenix Plugin: ' + this.name);
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {};
      }
    }, {
      key: 'defaultOptions',
      get: function get() {
        return {};
      }
    }]);

    function PhoenixPlugin() {
      //

      _classCallCheck(this, PhoenixPlugin);
    }

    _createClass(PhoenixPlugin, [{
      key: 'boot',
      value: function boot(phoenix) {
        var _this3 = this;

        this.phoenix = phoenix;

        var name = this.constructor.is.toLowerCase();

        // Merge to global options
        this.phoenix.options[name] = $.extend(true, {}, this.constructor.defaultOptions, this.phoenix.options[name]);

        // Created hook
        this.created();

        // DOM Ready hook
        $(function () {
          return _this3.ready();
        });

        // Phoenix onload hook
        this.phoenix.on('loaded', this.loaded);
      }
    }, {
      key: 'created',
      value: function created() {
        //
      }
    }, {
      key: 'ready',
      value: function ready() {
        //
      }
    }, {
      key: 'loaded',
      value: function loaded() {
        //
      }
    }], [{
      key: 'createProxies',
      value: function createProxies(phoenix, plugin) {
        if (plugin.constructor.proxies === undefined) {
          return this;
        }

        this.resetProxies(phoenix, plugin);

        phoenix[plugin.constructor.is] = plugin;

        var proxies = plugin.constructor.proxies;

        var _loop = function _loop(name) {
          if (!proxies.hasOwnProperty(name)) {
            return 'continue';
          }

          var origin = proxies[name];

          if (phoenix[name] !== undefined) {
            throw new Error('Property: ' + name + ' has exists in Phoenix instance.');
          }

          if (typeof origin === 'function') {
            phoenix[name] = origin;
          } else if (plugin[origin] !== undefined) {
            if (typeof plugin[origin] === 'function') {
              phoenix[name] = function () {
                return plugin[origin].apply(plugin, arguments);
              };
            } else {
              Object.defineProperties(phoenix, name, {
                get: function get() {
                  return plugin[origin];
                },
                set: function set(value) {
                  plugin[origin] = value;
                }
              });
            }
          } else {
            throw new Error('Proxy property: "' + origin + '" not found in Plugin: ' + plugin.constructor.name);
          }
        };

        for (var name in proxies) {
          var _ret = _loop(name);

          if (_ret === 'continue') continue;
        }
      }
    }, {
      key: 'resetProxies',
      value: function resetProxies(phoenix, plugin) {
        var name = typeof plugin === 'string' ? plugin : plugin.constructor.is;

        if (phoenix[name]) {
          plugin = phoenix[name];
        }

        if (plugin.constructor.proxies === undefined) {
          return;
        }

        for (var _name in plugin.constructor.proxies) {
          delete phoenix[_name];
        }

        delete phoenix[plugin.constructor.is];
      }
    }]);

    return PhoenixPlugin;
  }();

  var PhoenixJQueryPlugin = function (_PhoenixPlugin) {
    _inherits(PhoenixJQueryPlugin, _PhoenixPlugin);

    function PhoenixJQueryPlugin() {
      _classCallCheck(this, PhoenixJQueryPlugin);

      return _possibleConstructorReturn(this, (PhoenixJQueryPlugin.__proto__ || Object.getPrototypeOf(PhoenixJQueryPlugin)).apply(this, arguments));
    }

    _createClass(PhoenixJQueryPlugin, [{
      key: 'createPlugin',
      value: function createPlugin(selector) {
        var _$;

        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

        options.mainSelector = selector;

        for (var _len2 = arguments.length, args = Array(_len2 > 2 ? _len2 - 2 : 0), _key2 = 2; _key2 < _len2; _key2++) {
          args[_key2 - 2] = arguments[_key2];
        }

        return (_$ = $(selector))[this.constructor.pluginName].apply(_$, [options, this.phoenix].concat(args));
      }
    }], [{
      key: 'install',
      value: function install(phoenix) {
        var instance = _get(PhoenixJQueryPlugin.__proto__ || Object.getPrototypeOf(PhoenixJQueryPlugin), 'install', this).call(this, phoenix);

        phoenix.plugin(this.pluginName, this.pluginClass);

        return instance;
      }
    }, {
      key: 'pluginName',

      /**
       * Plugin name.
       * @returns {string|null}
       */
      get: function get() {
        throw new Error('Please provide a plugin name.');
      }
    }, {
      key: 'pluginClass',
      get: function get() {
        throw new Error('Please provide a class as plugin instance.');
      }
    }]);

    return PhoenixJQueryPlugin;
  }(PhoenixPlugin);

  window.PhoenixPlugin = PhoenixPlugin;
  window.PhoenixJQueryPlugin = PhoenixJQueryPlugin;
})(jQuery);

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function ($) {
  var PhoenixHelper = function (_PhoenixPlugin2) {
    _inherits(PhoenixHelper, _PhoenixPlugin2);

    _createClass(PhoenixHelper, null, [{
      key: 'is',
      get: function get() {
        return 'Helper';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          confirm: 'confirm',
          keepAlive: 'keepAlive',
          stopKeepAlive: 'stopKeepAlive',
          loadScript: 'loadScript'
        };
      }
    }, {
      key: 'defaultOptions',
      get: function get() {
        return {};
      }
    }]);

    function PhoenixHelper() {
      _classCallCheck(this, PhoenixHelper);

      var _this5 = _possibleConstructorReturn(this, (PhoenixHelper.__proto__ || Object.getPrototypeOf(PhoenixHelper)).call(this));

      _this5.aliveHandle = null;
      return _this5;
    }

    /**
     * Confirm popup.
     *
     * @param {string}   message
     * @param {Function} callback
     */


    _createClass(PhoenixHelper, [{
      key: 'confirm',
      value: function (_confirm) {
        function confirm(_x3, _x4) {
          return _confirm.apply(this, arguments);
        }

        confirm.toString = function () {
          return _confirm.toString();
        };

        return confirm;
      }(function (message, callback) {
        message = message || 'Are you sure?';

        var confirmed = confirm(message);

        callback(confirmed);

        return confirmed;
      })
    }, {
      key: 'loadScript',
      value: function loadScript(urls) {
        var _this6 = this;

        if (typeof urls === 'string') {
          urls = [urls];
        }

        var promises = [];
        var data = {};
        data[this.phoenix.asset('version')] = '1';

        urls.forEach(function (url) {
          promises.push($.getScript({
            url: _this6.addUriBase(url),
            cache: true,
            data: data
          }));
        });

        return $.when.apply($, promises);
      }
    }, {
      key: 'addUriBase',
      value: function addUriBase(uri) {
        var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'path';

        if (uri.substr(0, 2) === '/' || uri.substr(0, 4) === 'http') {
          return uri;
        }

        return this.phoenix.asset(type) + '/' + uri;
      }

      /**
       * Keep alive.
       *
       * @param {string} url
       * @param {Number} time
       *
       * @return {number}
       */

    }, {
      key: 'keepAlive',
      value: function keepAlive(url, time) {
        return this.aliveHandle = window.setInterval(function () {
          return $.get('/');
        }, time);
      }

      /**
       * Stop keep alive
       */

    }, {
      key: 'stopKeepAlive',
      value: function stopKeepAlive() {
        clearInterval(this.aliveHandle);
      }
    }]);

    return PhoenixHelper;
  }(PhoenixPlugin);

  window.PhoenixHelper = PhoenixHelper;
})(jQuery);

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function ($) {
  var PhoenixUI = function (_PhoenixPlugin3) {
    _inherits(PhoenixUI, _PhoenixPlugin3);

    _createClass(PhoenixUI, null, [{
      key: 'is',
      get: function get() {
        return 'UI';
      }
    }, {
      key: 'defaultOptions',
      get: function get() {
        return {
          messageSelector: '.message-wrap'
        };
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          addMessage: 'renderMessage'
        };
      }
    }]);

    function PhoenixUI() {
      _classCallCheck(this, PhoenixUI);

      var _this7 = _possibleConstructorReturn(this, (PhoenixUI.__proto__ || Object.getPrototypeOf(PhoenixUI)).call(this));

      _this7.aliveHandle = null;
      return _this7;
    }

    _createClass(PhoenixUI, [{
      key: 'ready',
      value: function ready() {
        _get(PhoenixUI.prototype.__proto__ || Object.getPrototypeOf(PhoenixUI.prototype), 'ready', this).call(this);

        this.messageContainer = $(this.options.messageSelector);
      }

      /**
       * Show Validation response.
       *
       * @param {PhoenixValidation} validation
       * @param {string}            state
       * @param {jQuery}            $input
       * @param {string}            help
       */

    }, {
      key: 'showValidateResponse',
      value: function showValidateResponse(validation, state, $input, help) {
        throw new Error('Please implement this method.');
      }

      /**
       * Add validate effect to input, just override this method to fit other templates.
       *
       * @param {jQuery} $control
       * @param {jQuery} $input
       * @param {string} icon
       * @param {string} type
       * @param {string} help
       */

    }, {
      key: 'addValidateResponse',
      value: function addValidateResponse($control, $input, icon, type, help) {
        throw new Error('Please implement this method.');
      }

      /**
       * Remove validation response.
       *
       * @param {jQuery} $element
       */

    }, {
      key: 'removeValidateResponse',
      value: function removeValidateResponse($element) {
        throw new Error('Please implement this method.');
      }

      /**
       * Render message.
       *
       * @param {string|Array} msg
       * @param {string}       type
       */

    }, {
      key: 'renderMessage',
      value: function renderMessage(msg, type) {
        throw new Error('Please implement this method.');
      }

      /**
       * Remove all messages.
       */

    }, {
      key: 'removeMessages',
      value: function removeMessages() {
        throw new Error('Please implement this method.');
      }

      /**
       * Toggle filter bar.
       *
       * @param {jQuery} container
       * @param {jQuery} button
       */

    }, {
      key: 'toggleFilter',
      value: function toggleFilter(container, button) {
        var showClass = button.attr('data-class-show') || 'btn-primary';
        var hideClass = button.attr('data-class-hide') || 'btn-default';

        var icon = button.find('span.filter-button-icon');
        var iconShowClass = icon.attr('data-class-show') || 'fa fa-angle-up';
        var iconHideClass = icon.attr('data-class-hide') || 'fa fa-angle-down';

        if (container.hasClass('shown')) {
          button.removeClass(showClass).addClass(hideClass);
          container.hide('fast');
          container.removeClass('shown');

          icon.removeClass(iconShowClass).addClass(iconHideClass);
        } else {
          button.removeClass(hideClass).addClass(showClass);
          container.show('fast');
          container.addClass('shown');

          icon.removeClass(iconHideClass).addClass(iconShowClass);
        }
      }

      /**
       * Confirm popup.
       *
       * TODO: Move to core.
       *
       * @param {string}   message
       * @param {Function} callback
       */

    }, {
      key: 'confirm',
      value: function (_confirm2) {
        function confirm(_x6, _x7) {
          return _confirm2.apply(this, arguments);
        }

        confirm.toString = function () {
          return _confirm2.toString();
        };

        return confirm;
      }(function (message, callback) {
        message = message || 'Are you sure?';

        var confirmed = confirm(message);

        callback(confirmed);

        return confirmed;
      })

      /**
       * Keep alive.
       *
       * @param {string} url
       * @param {Number} time
       *
       * @return {number}
       */

    }, {
      key: 'keepAlive',
      value: function keepAlive(url, time) {
        return this.aliveHandle = window.setInterval(function () {
          return $.get('/');
        }, time);
      }
    }, {
      key: 'stopKeepAlive',
      value: function stopKeepAlive() {
        clearInterval(this.aliveHandle);
      }
    }]);

    return PhoenixUI;
  }(PhoenixPlugin);

  window.PhoenixUI = PhoenixUI;
})(jQuery);

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * Phoenix.Router
 */
(function ($) {
  "use strict";

  var PhoenixRouter = function (_PhoenixPlugin4) {
    _inherits(PhoenixRouter, _PhoenixPlugin4);

    function PhoenixRouter() {
      _classCallCheck(this, PhoenixRouter);

      return _possibleConstructorReturn(this, (PhoenixRouter.__proto__ || Object.getPrototypeOf(PhoenixRouter)).apply(this, arguments));
    }

    _createClass(PhoenixRouter, [{
      key: 'ready',
      value: function ready() {
        var _this9 = this;

        $(window).on('popstate', function (e) {
          return _this9.phoenix.on('router.popstate', e);
        });
      }

      /**
       * Add a route.
       *
       * @param route
       * @param url
       *
       * @returns {PhoenixRouter}
       */

    }, {
      key: 'add',
      value: function add(route, url) {
        var data = {};
        data[route] = url;

        this.phoenix.data('phoenix.routes', data);

        return this;
      }

      /**
       * Get route.
       *
       * @param route
       * @param query
       * @returns {String|PhoenixRouter}
       */

    }, {
      key: 'route',
      value: function route(_route) {
        var query = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

        var url = this.phoenix.data('phoenix.routes')[_route];

        if (url === undefined) {
          throw new Error('Route: "' + _route + '" not found');
        }

        return this.addQuery(url, query);
      }
    }, {
      key: 'addQuery',
      value: function addQuery(url) {
        var query = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

        if (query === null) {
          return url;
        }

        query = $.param(query);

        return url + (/\?/.test(url) ? '&' + query : '?' + query);
      }
    }, {
      key: 'push',
      value: function push(data) {
        if (typeof data === 'string') {
          data = { uri: data };
        }

        window.history.pushState(data.state || null, data.title || null, data.uri || this.route(data.route, data.params));

        return this;
      }
    }, {
      key: 'replace',
      value: function replace(data) {
        if (typeof data === 'string') {
          data = { uri: data };
        }

        window.history.replaceState(data.state || null, data.title || null, data.uri || this.route(data.route, data.params));

        return this;
      }
    }, {
      key: 'state',
      value: function state() {
        return window.history.state;
      }
    }, {
      key: 'back',
      value: function back() {
        window.history.back();
      }
    }, {
      key: 'forward',
      value: function forward() {
        window.history.forward();
      }
    }, {
      key: 'go',
      value: function go(num) {
        window.history.go(num);
      }
    }], [{
      key: 'is',
      get: function get() {
        return 'Router';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          addRoute: 'add',
          route: 'route'
        };
      }
    }]);

    return PhoenixRouter;
  }(PhoenixPlugin);

  window.PhoenixRouter = PhoenixRouter;
})(jQuery);

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * Phoenix.Translator
 */
(function () {
  "use strict";

  var PhoenixTranslator = function (_PhoenixPlugin5) {
    _inherits(PhoenixTranslator, _PhoenixPlugin5);

    _createClass(PhoenixTranslator, null, [{
      key: 'is',
      get: function get() {
        return 'Translator';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          trans: 'translate',
          __: 'translate',
          addLanguage: 'addKey'
        };
      }
    }]);

    function PhoenixTranslator() {
      _classCallCheck(this, PhoenixTranslator);

      var _this10 = _possibleConstructorReturn(this, (PhoenixTranslator.__proto__ || Object.getPrototypeOf(PhoenixTranslator)).call(this));

      _this10.keys = {};
      return _this10;
    }

    /**
     * Translate a string.
     *
     * @param {string} text
     * @param {Array}  args
     * @returns {string}
     */


    _createClass(PhoenixTranslator, [{
      key: 'translate',
      value: function translate(text) {
        var key = this.normalize(text);

        for (var _len3 = arguments.length, args = Array(_len3 > 1 ? _len3 - 1 : 0), _key3 = 1; _key3 < _len3; _key3++) {
          args[_key3 - 1] = arguments[_key3];
        }

        if (args.length) {
          return this.sprintf.apply(this, [text].concat(args));
        }

        var langs = this.phoenix.data('phoenix.languages');

        if (langs[key]) {
          return langs[key];
        }

        return text;
      }
    }, {
      key: 'sprintf',
      value: function (_sprintf) {
        function sprintf(_x10) {
          return _sprintf.apply(this, arguments);
        }

        sprintf.toString = function () {
          return _sprintf.toString();
        };

        return sprintf;
      }(function (text) {
        for (var _len4 = arguments.length, args = Array(_len4 > 1 ? _len4 - 1 : 0), _key4 = 1; _key4 < _len4; _key4++) {
          args[_key4 - 1] = arguments[_key4];
        }

        args[0] = this.translate(text);

        return sprintf.apply(sprintf, args);
      })

      /**
       * Add language key.
       *
       * @param {string} key
       * @param {string} value
       *
       * @return {Phoenix.Translator}
       */

    }, {
      key: 'addKey',
      value: function addKey(key, value) {
        var data = {};
        data[this.normalize(key)] = value;

        this.phoenix.data('phoenix.languages', data);

        return this;
      }

      /**
       * Replace all symbols to dot(.).
       *
       * @param {string} text
       *
       * @return {string}
       */

    }, {
      key: 'normalize',
      value: function normalize(text) {
        return text.replace(/[^A-Z0-9]+/ig, '.');
      }
    }]);

    return PhoenixTranslator;
  }(PhoenixPlugin);

  window.PhoenixTranslator = PhoenixTranslator;
})();

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function () {
  var PhoenixLegacy = function (_PhoenixPlugin6) {
    _inherits(PhoenixLegacy, _PhoenixPlugin6);

    function PhoenixLegacy() {
      _classCallCheck(this, PhoenixLegacy);

      return _possibleConstructorReturn(this, (PhoenixLegacy.__proto__ || Object.getPrototypeOf(PhoenixLegacy)).apply(this, arguments));
    }

    _createClass(PhoenixLegacy, [{
      key: 'created',
      value: function created() {
        var _this12 = this;

        var phoenix = this.phoenix;

        phoenix.Theme = phoenix.UI;

        var formInited = false;
        var gridInited = false;

        phoenix.on('jquery.plugin.created', function (event) {
          // Legacy Form polyfill
          if (!formInited && event.name === 'form') {
            ['delete', 'get', 'patch', 'post', 'put', 'sendDelete', 'submit'].forEach(function (method) {
              phoenix[method] = function () {
                var _event$instance;

                _this12.constructor.warn('Phoenix', method);
                (_event$instance = event.instance)[method].apply(_event$instance, arguments);
              };
            });

            formInited = true;
          }

          // Legacy Grid polyfill
          if (!gridInited && event.name === 'grid') {
            ['toggleFilter', 'sort', 'checkRow', 'updateRow', 'doTask', 'batch', 'copyRow', 'deleteList', 'deleteRow', 'toggleAll', 'countChecked', 'getChecked', 'hasChecked', 'reorderAll', 'reorder'].forEach(function (method) {
              phoenix.Grid[method] = function () {
                var _event$instance2;

                _this12.constructor.warn('Phoenix.Grid', method);
                (_event$instance2 = event.instance)[method].apply(_event$instance2, arguments);
              };
            });

            gridInited = true;
          }
        });
      }
    }, {
      key: 'ready',
      value: function ready() {
        _get(PhoenixLegacy.prototype.__proto__ || Object.getPrototypeOf(PhoenixLegacy.prototype), 'ready', this).call(this);
      }
    }], [{
      key: 'warn',
      value: function warn(obj, method) {
        console.warn('Calling ' + obj + '.' + method + '() is deprecated.');
      }
    }, {
      key: 'is',
      get: function get() {
        return 'Legacy';
      }
    }]);

    return PhoenixLegacy;
  }(PhoenixPlugin);

  window.PhoenixLegacy = PhoenixLegacy;
})();
//# sourceMappingURL=phoenix.js.map
