'use strict';

var _get = function get(object, property, receiver) { if (object === null) object = Function.prototype; var desc = Object.getOwnPropertyDescriptor(object, property); if (desc === undefined) { var parent = Object.getPrototypeOf(object); if (parent === null) { return undefined; } else { return get(parent, property, receiver); } } else if ("value" in desc) { return desc.value; } else { var getter = desc.get; if (getter === undefined) { return undefined; } return getter.call(receiver); } };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

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
      var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

      _classCallCheck(this, PhoenixCore);

      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this._listeners = {};
    }

    _createClass(PhoenixCore, [{
      key: 'use',
      value: function use(plugin) {
        var _this = this;

        if (Array.isArray(plugin)) {
          plugin.forEach(function (p) {
            return _this.use(p);
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
      value: function (_confirm) {
        function confirm(_x, _x2) {
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

      /**
       * Add message.
       *
       * TODO: Move to core.
       *
       * @param {string} msg
       * @param {string} type
       *
       * @returns {PhoenixForm}
       */

    }, {
      key: 'addMessage',
      value: function addMessage(msg, type) {
        var messageContainer = $(this.options.selector.message);

        Phoenix.Theme.renderMessage(messageContainer, msg, type);

        return this;
      }

      /**
       * Remove all messages.
       *
       * TODO: Move to core.
       *
       * @returns {PhoenixForm}
       */

    }, {
      key: 'removeMessages',
      value: function removeMessages() {
        var messageContainer = $(this.options.selector.message);

        Phoenix.Theme.removeMessages(messageContainer);

        return this;
      }

      /**
       * Keep alive.
       *
       * TODO: Move to core.
       *
       * @param {string} url
       * @param {Number} time
       *
       * @return {number}
       */

    }, {
      key: 'keepAlive',
      value: function keepAlive(url, time) {
        return window.setInterval(function () {
          var r = void 0;

          try {
            r = new XMLHttpRequest();
          } catch (e) {
            // Nothing
          }

          if (r) {
            r.open('GET', url, true);
            r.send(null);
          }
        }, time);
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
        var _this2 = this;

        this.phoenix = phoenix;

        var name = this.constructor.is.toLowerCase();

        // Merge to global options
        this.phoenix.options[name] = $.extend(true, this.phoenix.options[name], this.constructor.defaultOptions, this.phoenix.options[name]);

        // Created hook
        this.created();

        // DOM Ready hook
        $(function () {
          return _this2.ready();
        });

        // Phoenix onload hook
        // todo: add loaded
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

  window.Phoenix = new PhoenixCore();
  window.PhoenixPlugin = PhoenixPlugin;
  window.PhoenixJQueryPlugin = PhoenixJQueryPlugin;
})(jQuery);

(function ($) {
  var PhoenixLegacy = function (_PhoenixPlugin2) {
    _inherits(PhoenixLegacy, _PhoenixPlugin2);

    function PhoenixLegacy() {
      _classCallCheck(this, PhoenixLegacy);

      return _possibleConstructorReturn(this, (PhoenixLegacy.__proto__ || Object.getPrototypeOf(PhoenixLegacy)).apply(this, arguments));
    }

    _createClass(PhoenixLegacy, [{
      key: 'created',
      value: function created() {
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

                return (_event$instance = event.instance)[method].apply(_event$instance, arguments);
              };
            });

            formInited = true;
          }

          // Legacy Grid polyfill
          if (!gridInited && event.name === 'grid') {
            ['toggleFilter', 'sort', 'checkRow', 'updateRow', 'doTask', 'batch', 'copyRow', 'deleteList', 'deleteRow', 'toggleAll', 'countChecked', 'getChecked', 'hasChecked', 'reorderAll', 'reorder'].forEach(function (method) {
              phoenix.Grid[method] = function () {
                var _event$instance2;

                return (_event$instance2 = event.instance)[method].apply(_event$instance2, arguments);
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
      key: 'is',
      get: function get() {
        return 'Legacy';
      }
    }]);

    return PhoenixLegacy;
  }(PhoenixPlugin);

  window.PhoenixLegacy = PhoenixLegacy;
})(jQuery);
//# sourceMappingURL=phoenix.js.map
