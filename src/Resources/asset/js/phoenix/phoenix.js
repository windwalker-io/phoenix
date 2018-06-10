'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

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
        var _this3 = this;

        if (Array.isArray(event)) {
          event.forEach(function (e) {
            return _this3.on(e, handler);
          });
          return this;
        }

        if (this._listeners[event] === undefined) {
          this._listeners[event] = [];
        }

        this._listeners[event].push(handler);

        return this;
      }
    }, {
      key: 'once',
      value: function once(event, handler) {
        var _this4 = this;

        if (Array.isArray(event)) {
          event.forEach(function (e) {
            return _this4.once(e, handler);
          });
          return this;
        }

        handler._once = true;

        this.on(event, handler);
      }
    }, {
      key: 'off',
      value: function off(event) {
        var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

        if (callback !== null) {
          this._listeners[event] = this.listeners(event).filter(function (listener) {
            return listener !== callback;
          });
          return this;
        }

        delete this._listeners[event];

        return this;
      }
    }, {
      key: 'trigger',
      value: function trigger(event) {
        var _this5 = this;

        for (var _len = arguments.length, args = Array(_len > 1 ? _len - 1 : 0), _key = 1; _key < _len; _key++) {
          args[_key - 1] = arguments[_key];
        }

        if (Array.isArray(event)) {
          event.forEach(function (e) {
            return _this5.trigger(e);
          });
          return this;
        }

        this.listeners(event).forEach(function (listener) {
          listener.apply(undefined, args);
        });

        // Remove once
        this._listeners[event] = this.listeners(event).filter(function (listener) {
          return listener._once !== true;
        });

        if (this.data('windwalker.debug')) {
          console.debug('[Phoenix Event] ' + event, args, this.listeners(event));
        }

        return this;
      }
    }, {
      key: 'listeners',
      value: function listeners(event) {
        if (typeof event !== 'string') {
          throw new Error('get listeners event name should only use string.');
        }

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
            for (var _len2 = arguments.length, args = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
              args[_key2] = arguments[_key2];
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
        var _this6 = this;

        this.phoenix = phoenix;

        var name = this.constructor.is.toLowerCase();

        // Merge to global options
        this.phoenix.options[name] = $.extend(true, {}, this.constructor.defaultOptions, this.phoenix.options[name]);

        // Created hook
        this.created();

        // DOM Ready hook
        $(function () {
          return _this6.ready();
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

        for (var _len3 = arguments.length, args = Array(_len3 > 2 ? _len3 - 2 : 0), _key3 = 2; _key3 < _len3; _key3++) {
          args[_key3 - 2] = arguments[_key3];
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
          loadScript: 'loadScript',
          sprintf: 'sprintf',
          vsprintf: 'vsprintf'
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

      var _this8 = _possibleConstructorReturn(this, (PhoenixHelper.__proto__ || Object.getPrototypeOf(PhoenixHelper)).call(this));

      _this8.aliveHandle = null;
      return _this8;
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
        function confirm(_x4, _x5) {
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
        var _this9 = this;

        var autoConvert = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

        if (typeof urls === 'string') {
          urls = [urls];
        }

        var promises = [];
        var data = {};
        var endsWith = function endsWith(str, suffix) {
          return str.indexOf(suffix, str.length - suffix.length) >= 0;
        };
        data[this.phoenix.asset('version')] = '1';

        urls.forEach(function (url) {
          var ext = url.split('.').pop();
          var loadUri = url;

          if (autoConvert) {
            var assetFile = void 0,
                assetMinFile = void 0;

            if (endsWith(url, '.min.' + ext)) {
              assetMinFile = url;
              assetFile = url.slice(0, -('.min.' + ext).length) + '.' + ext;
            } else {
              assetFile = url;
              assetMinFile = url.slice(0, -('.' + ext).length) + '.min.' + ext;
            }

            loadUri = _this9.phoenix.data('windwalker.debug') ? assetFile : assetMinFile;
          }

          promises.push($.getScript({
            url: _this9.addUriBase(loadUri),
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

        if (uri.substr(0, 2) === '//' || uri.substr(0, 4) === 'http') {
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

  // Fork sprintf here to reduce requests
  (function () {
    var re = {
      not_string: /[^s]/,
      not_bool: /[^t]/,
      not_type: /[^T]/,
      not_primitive: /[^v]/,
      number: /[diefg]/,
      numeric_arg: /[bcdiefguxX]/,
      json: /[j]/,
      not_json: /[^j]/,
      text: /^[^\x25]+/,
      modulo: /^\x25{2}/,
      placeholder: /^\x25(?:([1-9]\d*)\$|\(([^\)]+)\))?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-gijostTuvxX])/,
      key: /^([a-z_][a-z_\d]*)/i,
      key_access: /^\.([a-z_][a-z_\d]*)/i,
      index_access: /^\[(\d+)\]/,
      sign: /^[\+\-]/
    };

    function sprintf(key) {
      // `arguments` is not an array, but should be fine for this call
      return sprintf_format(sprintf_parse(key), arguments);
    }

    function vsprintf(fmt, argv) {
      return sprintf.apply(null, [fmt].concat(argv || []));
    }

    function sprintf_format(parse_tree, argv) {
      var cursor = 1,
          tree_length = parse_tree.length,
          arg,
          output = '',
          i,
          k,
          match,
          pad,
          pad_character,
          pad_length,
          is_positive,
          sign;
      for (i = 0; i < tree_length; i++) {
        if (typeof parse_tree[i] === 'string') {
          output += parse_tree[i];
        } else if (Array.isArray(parse_tree[i])) {
          match = parse_tree[i]; // convenience purposes only
          if (match[2]) {
            // keyword argument
            arg = argv[cursor];
            for (k = 0; k < match[2].length; k++) {
              if (!arg.hasOwnProperty(match[2][k])) {
                throw new Error(sprintf('[sprintf] property "%s" does not exist', match[2][k]));
              }
              arg = arg[match[2][k]];
            }
          } else if (match[1]) {
            // positional argument (explicit)
            arg = argv[match[1]];
          } else {
            // positional argument (implicit)
            arg = argv[cursor++];
          }

          if (re.not_type.test(match[8]) && re.not_primitive.test(match[8]) && arg instanceof Function) {
            arg = arg();
          }

          if (re.numeric_arg.test(match[8]) && typeof arg !== 'number' && isNaN(arg)) {
            throw new TypeError(sprintf('[sprintf] expecting number but found %T', arg));
          }

          if (re.number.test(match[8])) {
            is_positive = arg >= 0;
          }

          switch (match[8]) {
            case 'b':
              arg = parseInt(arg, 10).toString(2);
              break;
            case 'c':
              arg = String.fromCharCode(parseInt(arg, 10));
              break;
            case 'd':
            case 'i':
              arg = parseInt(arg, 10);
              break;
            case 'j':
              arg = JSON.stringify(arg, null, match[6] ? parseInt(match[6]) : 0);
              break;
            case 'e':
              arg = match[7] ? parseFloat(arg).toExponential(match[7]) : parseFloat(arg).toExponential();
              break;
            case 'f':
              arg = match[7] ? parseFloat(arg).toFixed(match[7]) : parseFloat(arg);
              break;
            case 'g':
              arg = match[7] ? String(Number(arg.toPrecision(match[7]))) : parseFloat(arg);
              break;
            case 'o':
              arg = (parseInt(arg, 10) >>> 0).toString(8);
              break;
            case 's':
              arg = String(arg);
              arg = match[7] ? arg.substring(0, match[7]) : arg;
              break;
            case 't':
              arg = String(!!arg);
              arg = match[7] ? arg.substring(0, match[7]) : arg;
              break;
            case 'T':
              arg = Object.prototype.toString.call(arg).slice(8, -1).toLowerCase();
              arg = match[7] ? arg.substring(0, match[7]) : arg;
              break;
            case 'u':
              arg = parseInt(arg, 10) >>> 0;
              break;
            case 'v':
              arg = arg.valueOf();
              arg = match[7] ? arg.substring(0, match[7]) : arg;
              break;
            case 'x':
              arg = (parseInt(arg, 10) >>> 0).toString(16);
              break;
            case 'X':
              arg = (parseInt(arg, 10) >>> 0).toString(16).toUpperCase();
              break;
          }
          if (re.json.test(match[8])) {
            output += arg;
          } else {
            if (re.number.test(match[8]) && (!is_positive || match[3])) {
              sign = is_positive ? '+' : '-';
              arg = arg.toString().replace(re.sign, '');
            } else {
              sign = '';
            }
            pad_character = match[4] ? match[4] === '0' ? '0' : match[4].charAt(1) : ' ';
            pad_length = match[6] - (sign + arg).length;
            pad = match[6] ? pad_length > 0 ? pad_character.repeat(pad_length) : '' : '';
            output += match[5] ? sign + arg + pad : pad_character === '0' ? sign + pad + arg : pad + sign + arg;
          }
        }
      }
      return output;
    }

    var sprintf_cache = Object.create(null);

    function sprintf_parse(fmt) {
      if (sprintf_cache[fmt]) {
        return sprintf_cache[fmt];
      }

      var _fmt = fmt,
          match,
          parse_tree = [],
          arg_names = 0;
      while (_fmt) {
        if ((match = re.text.exec(_fmt)) !== null) {
          parse_tree.push(match[0]);
        } else if ((match = re.modulo.exec(_fmt)) !== null) {
          parse_tree.push('%');
        } else if ((match = re.placeholder.exec(_fmt)) !== null) {
          if (match[2]) {
            arg_names |= 1;
            var field_list = [],
                replacement_field = match[2],
                field_match = [];
            if ((field_match = re.key.exec(replacement_field)) !== null) {
              field_list.push(field_match[1]);
              while ((replacement_field = replacement_field.substring(field_match[0].length)) !== '') {
                if ((field_match = re.key_access.exec(replacement_field)) !== null) {
                  field_list.push(field_match[1]);
                } else if ((field_match = re.index_access.exec(replacement_field)) !== null) {
                  field_list.push(field_match[1]);
                } else {
                  throw new SyntaxError('[sprintf] failed to parse named argument key');
                }
              }
            } else {
              throw new SyntaxError('[sprintf] failed to parse named argument key');
            }
            match[2] = field_list;
          } else {
            arg_names |= 2;
          }
          if (arg_names === 3) {
            throw new Error('[sprintf] mixing positional and named placeholders is not (yet) supported');
          }
          parse_tree.push(match);
        } else {
          throw new SyntaxError('[sprintf] unexpected placeholder');
        }

        _fmt = _fmt.substring(match[0].length);
      }
      return sprintf_cache[fmt] = parse_tree;
    }

    // Push to class
    PhoenixHelper.prototype.sprintf = sprintf;
    PhoenixHelper.prototype.vsprintf = vsprintf;
  })(PhoenixHelper);
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

      var _this10 = _possibleConstructorReturn(this, (PhoenixUI.__proto__ || Object.getPrototypeOf(PhoenixUI)).call(this));

      _this10.aliveHandle = null;
      return _this10;
    }

    _createClass(PhoenixUI, [{
      key: 'ready',
      value: function ready() {
        var _this11 = this;

        _get(PhoenixUI.prototype.__proto__ || Object.getPrototypeOf(PhoenixUI.prototype), 'ready', this).call(this);

        this.messageContainer = $(this.options.messageSelector);

        this.phoenix.on('validation.response', function (event) {
          _this11.showValidateResponse(event.validation, event.state, event.$input, event.help);
        });

        this.phoenix.on('validation.remove', function (event) {
          _this11.removeValidateResponse(event.$element);
        });
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
        this.messageContainer.children().each(function () {
          this.remove();
        });
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
        function confirm(_x8, _x9) {
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
        var _this13 = this;

        $(window).on('popstate', function (e) {
          return _this13.phoenix.on('router.popstate', e);
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
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

(function ($) {
  "use strict";

  var PhoenixAjax = function (_PhoenixPlugin5) {
    _inherits(PhoenixAjax, _PhoenixPlugin5);

    _createClass(PhoenixAjax, null, [{
      key: 'is',
      get: function get() {
        return 'Ajax';
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

    function PhoenixAjax() {
      _classCallCheck(this, PhoenixAjax);

      var _this14 = _possibleConstructorReturn(this, (PhoenixAjax.__proto__ || Object.getPrototypeOf(PhoenixAjax)).call(this));

      _this14.$ = $;

      _this14.config = {
        customMethod: false
      };

      _this14.data = {};

      _this14.headers = {
        GET: {},
        POST: {},
        PUT: {},
        PATCH: {},
        DELETE: {},
        HEAD: {},
        OPTIONS: {},
        _global: {}
      };
      return _this14;
    }

    _createClass(PhoenixAjax, [{
      key: 'ready',
      value: function ready() {
        _get(PhoenixAjax.prototype.__proto__ || Object.getPrototypeOf(PhoenixAjax.prototype), 'ready', this).call(this);

        this.headers._global['X-CSRF-Token'] = this.phoenix.data('csrf-token');
      }

      /**
       * Send a GET request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'get',
      value: function get(url, data, headers, options) {
        return this.request('GET', url, data, headers, options);
      }

      /**
       * Send a POST request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'post',
      value: function post(url, data, headers, options) {
        return this.request('POST', url, data, headers, options);
      }

      /**
       * Send a PUT request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'put',
      value: function put(url, data, headers, options) {
        return this.request('PUT', url, data, headers, options);
      }

      /**
       * Send a PATCH request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'patch',
      value: function patch(url, data, headers, options) {
        return this.request('PATCH', url, data, headers, options);
      }

      /**
       * Send a DELETE request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'sendDelete',
      value: function sendDelete(url, data, headers, options) {
        return this['delete'](url, data, headers, options);
      }

      /**
       * Send a DELETE request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'delete',
      value: function _delete(url, data, headers, options) {
        return this.request('DELETE', url, data, headers, options);
      }

      /**
       * Send a HEAD request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'head',
      value: function head(url, data, headers, options) {
        return this.request('HEAD', url, data, headers, options);
      }

      /**
       * Send a OPTIONS request.
       *
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'options',
      value: function options(url, data, headers, _options) {
        return this.request('OPTIONS', url, data, headers, _options);
      }

      /**
       * Send request.
       *
       * @param {string} method
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'sendRequest',
      value: function sendRequest(method, url, data, headers, options) {
        return this.request(method, url, data, headers, options);
      }

      /**
       * Send request.
       *
       * @param {string} method
       * @param {string} url
       * @param {Object} data
       * @param {Object} headers
       * @param {Object} options
       *
       * @returns {jqXHR}
       */

    }, {
      key: 'request',
      value: function request(method, url, data, headers, options) {
        options = options || {};
        headers = headers || {};
        data = data || {};
        url = url || '';

        if ((typeof url === 'undefined' ? 'undefined' : _typeof(url)) === 'object') {
          options = url;
          url = options.url;
        }

        options.data = typeof data === 'string' ? data : $.extend(true, {}, this.data, options.data, data);
        options.type = method.toUpperCase() || 'GET';
        var type = options.type;

        if (['POST', 'GET'].indexOf(options.type) === -1 && this.config.customMethod) {
          headers['X-HTTP-Method-Override'] = options.type;
          options.data._method = options.type;
          options.type = 'POST';
        }

        options.headers = $.extend(true, {}, this.headers._global, this.headers[type], options.headers, headers);

        return this.$.ajax(url, options);
      }

      /**
       * Set custom method with _method parameter.
       *
       * This method will return a clone of this object to help us send request once.
       *
       * @returns {Ajax}
       */

    }, {
      key: 'customMethod',
      value: function customMethod() {
        var clone = $.extend(true, {}, this);

        clone.config.customMethod = true;

        return clone;
      }
    }]);

    return PhoenixAjax;
  }(PhoenixPlugin);

  window.PhoenixAjax = PhoenixAjax;
})(jQuery);

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * PhoenixCrypto
 */
(function () {
  "use strict";

  var globalSerial = 1;

  var PhoenixCrypto = function (_PhoenixPlugin6) {
    _inherits(PhoenixCrypto, _PhoenixPlugin6);

    function PhoenixCrypto() {
      _classCallCheck(this, PhoenixCrypto);

      return _possibleConstructorReturn(this, (PhoenixCrypto.__proto__ || Object.getPrototypeOf(PhoenixCrypto)).apply(this, arguments));
    }

    _createClass(PhoenixCrypto, [{
      key: 'base64Encode',


      /**
       * Base64 encode.
       *
       * @param {string} string
       *
       * @returns {string}
       */
      value: function base64Encode(string) {
        return btoa(string);
      }

      /**
       * Base64 decode.
       *
       * @param {string} string
       *
       * @returns {string}
       */

    }, {
      key: 'base64Decode',
      value: function base64Decode(string) {
        return atob(string);
      }

      /**
       * XOR Cipher encrypt.
       *
       * @param {string} key
       * @param {string} data
       */

    }, {
      key: 'encrypt',
      value: function encrypt(key, data) {
        var _this16 = this;

        var code = data.split('').map(function (c, i) {
          return c.charCodeAt(0) ^ _this16.keyCharAt(key, i);
        }).join(',');

        return this.base64Encode(code);
      }

      /**
       * XOR Cipher decrypt.
       *
       * @param {string} key
       * @param {string} data
       *
       * @returns {string}
       */

    }, {
      key: 'decrypt',
      value: function decrypt(key, data) {
        var _this17 = this;

        data = this.base64Decode(data);

        data = data.split(',');

        return data.map(function (c, i) {
          return String.fromCharCode(c ^ _this17.keyCharAt(key, i));
        }).join("");
      }

      /**
       * Key char at.
       *
       * @param {string} key
       * @param {Number} i
       *
       * @returns {Number}
       */

    }, {
      key: 'keyCharAt',
      value: function keyCharAt(key, i) {
        return key.charCodeAt(Math.floor(i % key.length));
      }

      /**
       * UUID v4
       *
       * @see  https://gist.github.com/jed/982883
       *
       * @returns {string}
       */

    }, {
      key: 'uuid4',
      value: function uuid4() {
        return function b(a) {
          return a ? (a ^ Math.random() * 16 >> a / 4).toString(16) : ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, b);
        }();
      }

      /**
       * Get uniqid like php's unidid().
       *
       * @see http://locutus.io/php/misc/uniqid/
       *
       * @param {string}  prefix
       * @param {boolean} moreEntropy
       * @returns {*}
       */

    }, {
      key: 'uniqid',
      value: function uniqid() {
        var prefix = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
        var moreEntropy = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

        var retId = void 0;
        var _formatSeed = function _formatSeed(seed, reqWidth) {
          seed = parseInt(seed, 10).toString(16); // to hex str
          if (reqWidth < seed.length) {
            // so long we split
            return seed.slice(seed.length - reqWidth);
          }
          if (reqWidth > seed.length) {
            // so short we pad
            return Array(1 + (reqWidth - seed.length)).join('0') + seed;
          }
          return seed;
        };

        var $global = typeof window !== 'undefined' ? window : global;
        $global.$locutus = $global.$locutus || {};
        var $locutus = $global.$locutus;
        $locutus.php = $locutus.php || {};

        if (!$locutus.php.uniqidSeed) {
          // init seed with big random int
          $locutus.php.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
        }

        $locutus.php.uniqidSeed++;

        // start with prefix, add current milliseconds hex string
        retId = prefix;
        retId += _formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
        // add seed hex string
        retId += _formatSeed($locutus.php.uniqidSeed, 5);

        if (moreEntropy) {
          // for more entropy we add a float lower to 10
          retId += (Math.random() * 10).toFixed(8).toString();
        }

        return retId;
      }
    }, {
      key: 'serial',
      value: function serial() {
        return globalSerial++;
      }
    }], [{
      key: 'is',
      get: function get() {
        return 'Crypto';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          base64Encode: 'base64Encode',
          base64Decode: 'base64Decode',
          encrypt: 'encrypt',
          decrypt: 'decrypt',
          uuid4: 'uuid4',
          md5: 'md5',
          uniqid: 'uniqid'
        };
      }
    }, {
      key: 'defaultOptions',
      get: function get() {
        return {};
      }
    }]);

    return PhoenixCrypto;
  }(PhoenixPlugin);

  /**
   * Javascript-MD5
   *
   * @link  https://github.com/blueimp/JavaScript-MD5
   */


  (function (Crypto) {
    /*
     * Add integers, wrapping at 2^32. This uses 16-bit operations internally
     * to work around bugs in some JS interpreters.
     */
    function safe_add(x, y) {
      var lsw = (x & 0xFFFF) + (y & 0xFFFF);
      var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
      return msw << 16 | lsw & 0xFFFF;
    }

    /*
     * Bitwise rotate a 32-bit number to the left.
     */
    function bit_rol(num, cnt) {
      return num << cnt | num >>> 32 - cnt;
    }

    /*
     * These functions implement the four basic operations the algorithm uses.
     */
    function md5_cmn(q, a, b, x, s, t) {
      return safe_add(bit_rol(safe_add(safe_add(a, q), safe_add(x, t)), s), b);
    }

    function md5_ff(a, b, c, d, x, s, t) {
      return md5_cmn(b & c | ~b & d, a, b, x, s, t);
    }

    function md5_gg(a, b, c, d, x, s, t) {
      return md5_cmn(b & d | c & ~d, a, b, x, s, t);
    }

    function md5_hh(a, b, c, d, x, s, t) {
      return md5_cmn(b ^ c ^ d, a, b, x, s, t);
    }

    function md5_ii(a, b, c, d, x, s, t) {
      return md5_cmn(c ^ (b | ~d), a, b, x, s, t);
    }

    /*
     * Calculate the MD5 of an array of little-endian words, and a bit length.
     */
    function binl_md5(x, len) {
      /* append padding */
      x[len >> 5] |= 0x80 << len % 32;
      x[(len + 64 >>> 9 << 4) + 14] = len;

      var i;
      var olda;
      var oldb;
      var oldc;
      var oldd;
      var a = 1732584193;
      var b = -271733879;
      var c = -1732584194;
      var d = 271733878;

      for (i = 0; i < x.length; i += 16) {
        olda = a;
        oldb = b;
        oldc = c;
        oldd = d;

        a = md5_ff(a, b, c, d, x[i], 7, -680876936);
        d = md5_ff(d, a, b, c, x[i + 1], 12, -389564586);
        c = md5_ff(c, d, a, b, x[i + 2], 17, 606105819);
        b = md5_ff(b, c, d, a, x[i + 3], 22, -1044525330);
        a = md5_ff(a, b, c, d, x[i + 4], 7, -176418897);
        d = md5_ff(d, a, b, c, x[i + 5], 12, 1200080426);
        c = md5_ff(c, d, a, b, x[i + 6], 17, -1473231341);
        b = md5_ff(b, c, d, a, x[i + 7], 22, -45705983);
        a = md5_ff(a, b, c, d, x[i + 8], 7, 1770035416);
        d = md5_ff(d, a, b, c, x[i + 9], 12, -1958414417);
        c = md5_ff(c, d, a, b, x[i + 10], 17, -42063);
        b = md5_ff(b, c, d, a, x[i + 11], 22, -1990404162);
        a = md5_ff(a, b, c, d, x[i + 12], 7, 1804603682);
        d = md5_ff(d, a, b, c, x[i + 13], 12, -40341101);
        c = md5_ff(c, d, a, b, x[i + 14], 17, -1502002290);
        b = md5_ff(b, c, d, a, x[i + 15], 22, 1236535329);

        a = md5_gg(a, b, c, d, x[i + 1], 5, -165796510);
        d = md5_gg(d, a, b, c, x[i + 6], 9, -1069501632);
        c = md5_gg(c, d, a, b, x[i + 11], 14, 643717713);
        b = md5_gg(b, c, d, a, x[i], 20, -373897302);
        a = md5_gg(a, b, c, d, x[i + 5], 5, -701558691);
        d = md5_gg(d, a, b, c, x[i + 10], 9, 38016083);
        c = md5_gg(c, d, a, b, x[i + 15], 14, -660478335);
        b = md5_gg(b, c, d, a, x[i + 4], 20, -405537848);
        a = md5_gg(a, b, c, d, x[i + 9], 5, 568446438);
        d = md5_gg(d, a, b, c, x[i + 14], 9, -1019803690);
        c = md5_gg(c, d, a, b, x[i + 3], 14, -187363961);
        b = md5_gg(b, c, d, a, x[i + 8], 20, 1163531501);
        a = md5_gg(a, b, c, d, x[i + 13], 5, -1444681467);
        d = md5_gg(d, a, b, c, x[i + 2], 9, -51403784);
        c = md5_gg(c, d, a, b, x[i + 7], 14, 1735328473);
        b = md5_gg(b, c, d, a, x[i + 12], 20, -1926607734);

        a = md5_hh(a, b, c, d, x[i + 5], 4, -378558);
        d = md5_hh(d, a, b, c, x[i + 8], 11, -2022574463);
        c = md5_hh(c, d, a, b, x[i + 11], 16, 1839030562);
        b = md5_hh(b, c, d, a, x[i + 14], 23, -35309556);
        a = md5_hh(a, b, c, d, x[i + 1], 4, -1530992060);
        d = md5_hh(d, a, b, c, x[i + 4], 11, 1272893353);
        c = md5_hh(c, d, a, b, x[i + 7], 16, -155497632);
        b = md5_hh(b, c, d, a, x[i + 10], 23, -1094730640);
        a = md5_hh(a, b, c, d, x[i + 13], 4, 681279174);
        d = md5_hh(d, a, b, c, x[i], 11, -358537222);
        c = md5_hh(c, d, a, b, x[i + 3], 16, -722521979);
        b = md5_hh(b, c, d, a, x[i + 6], 23, 76029189);
        a = md5_hh(a, b, c, d, x[i + 9], 4, -640364487);
        d = md5_hh(d, a, b, c, x[i + 12], 11, -421815835);
        c = md5_hh(c, d, a, b, x[i + 15], 16, 530742520);
        b = md5_hh(b, c, d, a, x[i + 2], 23, -995338651);

        a = md5_ii(a, b, c, d, x[i], 6, -198630844);
        d = md5_ii(d, a, b, c, x[i + 7], 10, 1126891415);
        c = md5_ii(c, d, a, b, x[i + 14], 15, -1416354905);
        b = md5_ii(b, c, d, a, x[i + 5], 21, -57434055);
        a = md5_ii(a, b, c, d, x[i + 12], 6, 1700485571);
        d = md5_ii(d, a, b, c, x[i + 3], 10, -1894986606);
        c = md5_ii(c, d, a, b, x[i + 10], 15, -1051523);
        b = md5_ii(b, c, d, a, x[i + 1], 21, -2054922799);
        a = md5_ii(a, b, c, d, x[i + 8], 6, 1873313359);
        d = md5_ii(d, a, b, c, x[i + 15], 10, -30611744);
        c = md5_ii(c, d, a, b, x[i + 6], 15, -1560198380);
        b = md5_ii(b, c, d, a, x[i + 13], 21, 1309151649);
        a = md5_ii(a, b, c, d, x[i + 4], 6, -145523070);
        d = md5_ii(d, a, b, c, x[i + 11], 10, -1120210379);
        c = md5_ii(c, d, a, b, x[i + 2], 15, 718787259);
        b = md5_ii(b, c, d, a, x[i + 9], 21, -343485551);

        a = safe_add(a, olda);
        b = safe_add(b, oldb);
        c = safe_add(c, oldc);
        d = safe_add(d, oldd);
      }
      return [a, b, c, d];
    }

    /*
     * Convert an array of little-endian words to a string
     */
    function binl2rstr(input) {
      var i;
      var output = '';
      for (i = 0; i < input.length * 32; i += 8) {
        output += String.fromCharCode(input[i >> 5] >>> i % 32 & 0xFF);
      }
      return output;
    }

    /*
     * Convert a raw string to an array of little-endian words
     * Characters >255 have their high-byte silently ignored.
     */
    function rstr2binl(input) {
      var i;
      var output = [];
      output[(input.length >> 2) - 1] = undefined;
      for (i = 0; i < output.length; i += 1) {
        output[i] = 0;
      }
      for (i = 0; i < input.length * 8; i += 8) {
        output[i >> 5] |= (input.charCodeAt(i / 8) & 0xFF) << i % 32;
      }
      return output;
    }

    /*
     * Calculate the MD5 of a raw string
     */
    function rstr_md5(s) {
      return binl2rstr(binl_md5(rstr2binl(s), s.length * 8));
    }

    /*
     * Calculate the HMAC-MD5, of a key and some data (raw strings)
     */
    function rstr_hmac_md5(key, data) {
      var i;
      var bkey = rstr2binl(key);
      var ipad = [];
      var opad = [];
      var hash;
      ipad[15] = opad[15] = undefined;
      if (bkey.length > 16) {
        bkey = binl_md5(bkey, key.length * 8);
      }
      for (i = 0; i < 16; i += 1) {
        ipad[i] = bkey[i] ^ 0x36363636;
        opad[i] = bkey[i] ^ 0x5C5C5C5C;
      }
      hash = binl_md5(ipad.concat(rstr2binl(data)), 512 + data.length * 8);
      return binl2rstr(binl_md5(opad.concat(hash), 512 + 128));
    }

    /*
     * Convert a raw string to a hex string
     */
    function rstr2hex(input) {
      var hex_tab = '0123456789abcdef';
      var output = '';
      var x;
      var i;
      for (i = 0; i < input.length; i += 1) {
        x = input.charCodeAt(i);
        output += hex_tab.charAt(x >>> 4 & 0x0F) + hex_tab.charAt(x & 0x0F);
      }
      return output;
    }

    /*
     * Encode a string as utf-8
     */
    function str2rstr_utf8(input) {
      return decodeURIComponent(encodeURIComponent(input));
    }

    /*
     * Take string arguments and return either raw or hex encoded strings
     */
    function raw_md5(s) {
      return rstr_md5(str2rstr_utf8(s));
    }

    function hex_md5(s) {
      return rstr2hex(raw_md5(s));
    }

    function raw_hmac_md5(k, d) {
      return rstr_hmac_md5(str2rstr_utf8(k), str2rstr_utf8(d));
    }

    function hex_hmac_md5(k, d) {
      return rstr2hex(raw_hmac_md5(k, d));
    }

    function md5(string, key, raw) {
      if (!key) {
        if (!raw) {
          return hex_md5(string);
        }
        return raw_md5(string);
      }
      if (!raw) {
        return hex_hmac_md5(key, string);
      }
      return raw_hmac_md5(key, string);
    }

    if (typeof define === 'function' && define.amd) {
      define(function () {
        return md5;
      });
    } else if ((typeof module === 'undefined' ? 'undefined' : _typeof(module)) === 'object' && module.exports) {
      module.exports = md5;
    }

    PhoenixCrypto.prototype.md5 = md5;
  })(PhoenixCrypto);

  window.PhoenixCrypto = PhoenixCrypto;
})();

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

  var PhoenixTranslator = function (_PhoenixPlugin7) {
    _inherits(PhoenixTranslator, _PhoenixPlugin7);

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

      var _this18 = _possibleConstructorReturn(this, (PhoenixTranslator.__proto__ || Object.getPrototypeOf(PhoenixTranslator)).call(this));

      _this18.keys = {};
      return _this18;
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

        for (var _len4 = arguments.length, args = Array(_len4 > 1 ? _len4 - 1 : 0), _key4 = 1; _key4 < _len4; _key4++) {
          args[_key4 - 1] = arguments[_key4];
        }

        if (args.length) {
          return this.sprintf.apply(this, [text].concat(args));
        }

        return this.find(key);
      }

      /**
       * Sptintf language string.
       * @param {string} text
       * @param {Array} args
       */

    }, {
      key: 'sprintf',
      value: function sprintf(text) {
        for (var _len5 = arguments.length, args = Array(_len5 > 1 ? _len5 - 1 : 0), _key5 = 1; _key5 < _len5; _key5++) {
          args[_key5 - 1] = arguments[_key5];
        }

        return this.phoenix.vsprintf(this.find(text), args);
      }

      /**
       * Find text.
       * @param {string} key
       * @returns {*}
       */

    }, {
      key: 'find',
      value: function find(key) {
        var langs = this.phoenix.data('phoenix.languages');

        if (langs[key]) {
          return langs[key];
        }

        return key;
      }

      /**
       * Has language key.
       * @param {string} key
       * @returns {boolean}
       */

    }, {
      key: 'has',
      value: function has(key) {
        var langs = this.phoenix.data('phoenix.languages');

        return langs[key] !== undefined;
      }

      /**
       * Add language key.
       *
       * @param {string} key
       * @param {string} value
       *
       * @return {PhoenixTranslator}
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
  var formInited = false;
  var gridInited = false;

  var PhoenixLegacy = function (_PhoenixPlugin8) {
    _inherits(PhoenixLegacy, _PhoenixPlugin8);

    function PhoenixLegacy() {
      _classCallCheck(this, PhoenixLegacy);

      return _possibleConstructorReturn(this, (PhoenixLegacy.__proto__ || Object.getPrototypeOf(PhoenixLegacy)).apply(this, arguments));
    }

    _createClass(PhoenixLegacy, [{
      key: 'created',
      value: function created() {
        var _this20 = this;

        var phoenix = this.phoenix;

        phoenix.Theme = phoenix.UI;

        phoenix.on('jquery.plugin.created', function (event) {
          var debug = _this20.phoenix.data('windwalker.debug');

          // Legacy Form polyfill
          if (!formInited && event.name === 'form') {
            ['delete', 'get', 'patch', 'post', 'put', 'sendDelete', 'submit'].forEach(function (method) {
              phoenix[method] = function () {
                var _event$instance;

                debug ? _this20.constructor.warn('Phoenix', method) : null;
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

                debug ? _this20.constructor.warn('Phoenix.Grid', method) : null;
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
