'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(function ($) {
  var PhoenixForm = function (_PhoenixJQueryPlugin) {
    _inherits(PhoenixForm, _PhoenixJQueryPlugin);

    function PhoenixForm() {
      _classCallCheck(this, PhoenixForm);

      return _possibleConstructorReturn(this, (PhoenixForm.__proto__ || Object.getPrototypeOf(PhoenixForm)).apply(this, arguments));
    }

    _createClass(PhoenixForm, null, [{
      key: 'is',
      get: function get() {
        return 'Form';
      }
    }, {
      key: 'proxies',
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
      key: 'pluginName',
      get: function get() {
        return 'form';
      }
    }, {
      key: 'pluginClass',
      get: function get() {
        return PhoenixFormElement;
      }

      /**
       * Default options.
       * @returns {Object}
       */

    }, {
      key: 'defaultOptions',
      get: function get() {
        return {};
      }
    }]);

    return PhoenixForm;
  }(PhoenixJQueryPlugin);

  var PhoenixFormElement = function () {
    /**
     * Constructor.
     * @param {jQuery} $form
     * @param {Object} options
     */
    function PhoenixFormElement($form, options) {
      _classCallCheck(this, PhoenixFormElement);

      options = $.extend(true, {}, this.constructor.defaultOptions, options);

      this.form = $form;
      this.options = options;
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


    _createClass(PhoenixFormElement, [{
      key: 'submit',
      value: function submit(url, queries, method, customMethod) {
        var form = this.form;

        if (customMethod) {
          var methodInput = form.find('input[name="_method"]');

          if (!methodInput.length) {
            methodInput = $('<input name="_method" type="hidden">');

            form.append(methodInput);
          }

          methodInput.val(customMethod);
        }

        // Set queries into form.
        if (queries) {
          var input = void 0;

          $.each(queries, function (key, value) {
            input = form.find('input[name="' + key + '"]');

            if (!input.length) {
              input = $('<input name="' + key + '" type="hidden">');

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
      key: 'get',
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
      key: 'post',
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
      key: 'put',
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
      key: 'patch',
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
      key: 'sendDelete',
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
      key: 'delete',
      value: function _delete(url, queries) {
        return this.post(url, queries, 'DELETE');
      }
    }]);

    return PhoenixFormElement;
  }();

  window.PhoenixForm = PhoenixForm;
})(jQuery);
//# sourceMappingURL=form.js.map
