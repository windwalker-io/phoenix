"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { return function () { var Super = _getPrototypeOf(Derived), result; if (_isNativeReflectConstruct()) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Date.prototype.toString.call(Reflect.construct(Date, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */
(function ($) {
  var PhoenixForm = /*#__PURE__*/function (_PhoenixJQueryPlugin) {
    _inherits(PhoenixForm, _PhoenixJQueryPlugin);

    var _super = _createSuper(PhoenixForm);

    function PhoenixForm() {
      _classCallCheck(this, PhoenixForm);

      return _super.apply(this, arguments);
    }

    _createClass(PhoenixForm, null, [{
      key: "is",
      get: function get() {
        return 'Form';
      }
    }, {
      key: "proxies",
      get: function get() {
        return {
          form: 'createPlugin'
        };
      }
      /**
       * Plugin name.
       * @returns {string}
       */

    }, {
      key: "pluginName",
      get: function get() {
        return 'form';
      }
    }, {
      key: "pluginClass",
      get: function get() {
        return PhoenixFormElement;
      }
      /**
       * Default options.
       * @returns {Object}
       */

    }, {
      key: "defaultOptions",
      get: function get() {
        return {};
      }
    }]);

    return PhoenixForm;
  }(PhoenixJQueryPlugin);

  var PhoenixFormElement = /*#__PURE__*/function () {
    /**
     * Constructor.
     * @param {jQuery}      $form
     * @param {Object}      options
     * @param {PhoenixCore} phoenix
     */
    function PhoenixFormElement($form, options, phoenix) {
      _classCallCheck(this, PhoenixFormElement);

      // If form not found, create one
      if ($form.length === 0) {
        $form = $('<form>');

        if (options.mainSelector.indexOf('#') === 0) {
          $form.attr('id', options.mainSelector.substr(1));
          $form.attr('name', options.mainSelector.substr(1));
        }

        $form.attr('action', 'post');
        $form.attr('enctype', 'multipart/form-data');
        $form.attr('novalidate', 'true');
        $form.attr('action', phoenix.data('phoenix.uri')['full']);
        $form.css('display', 'none');
        var $csrf = $('<input type="hidden" value="" name="">');
        $csrf.attr('name', phoenix.data('csrfToken'));
        $form.append($csrf);
        $('body').append($form);
      }

      options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this.form = $form;
      this.options = options;
      this.bindEvents();
    }

    _createClass(PhoenixFormElement, [{
      key: "bindEvents",
      value: function bindEvents() {
        var _this = this;

        if (this.form.attr('data-toolbar')) {
          $(this.form.attr('data-toolbar')).find('*[data-action]').on('click', function (e) {
            _this.form.trigger('phoenix.submit', e.currentTarget);
          });
        }

        this.form.on('phoenix.submit', function (e, button) {
          var $button = $(button);
          var action = $button.attr('data-action');
          var target = $button.attr('data-target') || null;
          var query = $button.data('query') || {};
          query['task'] = $button.attr('data-task') || null;

          _this[action](target, query);
        });
      }
      /**
       * Make a request.
       *
       * @param  {string} url
       * @param  {Object} queries
       * @param  {string} method
       * @param  {string} customMethod
       *
       * @returns {boolean}
       */

    }, {
      key: "submit",
      value: function submit(url, queries, method, customMethod) {
        var _this2 = this;

        var form = this.form;

        if (customMethod) {
          var methodInput = form.find('input[name="_method"]');

          if (!methodInput.length) {
            methodInput = $('<input name="_method" type="hidden">');
            form.append(methodInput);
          }

          methodInput.val(customMethod);
        } // Set queries into form.


        if (queries) {
          var input;
          var flatted = this.constructor.flattenObject(queries);
          $.each(flatted, function (key, value) {
            var fieldName = _this2.constructor.buildFieldName(key);

            input = form.find('input[name="' + fieldName + '"]');

            if (!input.length) {
              input = $('<input name="' + fieldName + '" type="hidden">');
              form.append(input);
            }

            input.val(value);
          });
        }

        if (url) {
          form.attr('action', url);
        }

        if (method) {
          form.attr('method', method);
        }

        form.submit();
        return true;
      }
      /**
       * Make a GET request.
       *
       * @param  {string} url
       * @param  {Object} queries
       * @param  {string} customMethod
       *
       * @returns {boolean}
       */

    }, {
      key: "get",
      value: function get(url, queries, customMethod) {
        return this.submit(url, queries, 'GET', customMethod);
      }
      /**
       * Post form.
       *
       * @param  {string} url
       * @param  {Object} queries
       * @param  {string} customMethod
       *
       * @returns {boolean}
       */

    }, {
      key: "post",
      value: function post(url, queries, customMethod) {
        customMethod = customMethod || 'POST';
        return this.submit(url, queries, 'POST', customMethod);
      }
      /**
       * Make a PUT request.
       *
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: "put",
      value: function put(url, queries) {
        return this.post(url, queries, 'PUT');
      }
      /**
       * Make a PATCH request.
       *
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: "patch",
      value: function patch(url, queries) {
        return this.post(url, queries, 'PATCH');
      }
      /**
       * Make a DELETE request.
       *
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: "sendDelete",
      value: function sendDelete(url, queries) {
        return this['delete'](url, queries);
      }
      /**
       * Make a DELETE request.
       *
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: "delete",
      value: function _delete(url, queries) {
        return this.post(url, queries, 'DELETE');
      }
      /**
       * @see https://stackoverflow.com/a/53739792
       *
       * @param {Object} ob
       * @returns {Object}
       */

    }], [{
      key: "flattenObject",
      value: function flattenObject(ob) {
        var toReturn = {};

        for (var i in ob) {
          if (!ob.hasOwnProperty(i)) {
            continue;
          }

          if (_typeof(ob[i]) === 'object' && ob[i] != null) {
            var flatObject = this.flattenObject(ob[i]);

            for (var x in flatObject) {
              if (!flatObject.hasOwnProperty(x)) {
                continue;
              }

              toReturn[i + '/' + x] = flatObject[x];
            }
          } else {
            toReturn[i] = ob[i];
          }
        }

        return toReturn;
      }
    }, {
      key: "buildFieldName",
      value: function buildFieldName(field) {
        var names = field.split('/');
        var first = names.shift();
        return first + names.map(function (name) {
          return "[".concat(name, "]");
        }).join('');
      }
    }]);

    return PhoenixFormElement;
  }();

  window.PhoenixForm = PhoenixForm;
})(jQuery);
//# sourceMappingURL=form.js.map
