"use strict";

function _typeof(obj) { if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

/**
 * PhoenixValidation
 */
;

(function ($) {
  "use strict";
  /**
   * Default handlers
   *
   * @type {Object}
   */

  var handlers = {};
  /**
   * Class init.
   *
   * @param {jQuery} element
   * @param {Object} options
   * @constructor
   */

  var PhoenixValidation =
  /*#__PURE__*/
  function (_PhoenixJQueryPlugin) {
    _inherits(PhoenixValidation, _PhoenixJQueryPlugin);

    function PhoenixValidation() {
      _classCallCheck(this, PhoenixValidation);

      return _possibleConstructorReturn(this, _getPrototypeOf(PhoenixValidation).apply(this, arguments));
    }

    _createClass(PhoenixValidation, null, [{
      key: "is",
      get: function get() {
        return 'Validation';
      }
    }, {
      key: "pluginName",
      get: function get() {
        return 'validation';
      }
    }, {
      key: "pluginClass",
      get: function get() {
        return PhoenixValidationElement;
      }
    }, {
      key: "proxies",
      get: function get() {
        return {
          validation: 'createPlugin'
        };
      }
    }]);

    return PhoenixValidation;
  }(PhoenixJQueryPlugin);

  var PhoenixValidationElement =
  /*#__PURE__*/
  function () {
    _createClass(PhoenixValidationElement, null, [{
      key: "defaultOptions",
      get: function get() {
        return {
          events: ['change'],
          scroll: {
            enabled: true,
            offset: -100,
            duration: 1000
          }
        };
      }
    }]);

    function PhoenixValidationElement(element, options, phoenix) {
      _classCallCheck(this, PhoenixValidationElement);

      /**
       * Validate success.
       *
       * @type {string}
       */
      this.STATE_SUCCESS = 'success';
      /**
       * Validate fail.
       *
       * @type {string}
       */

      this.STATE_FAIL = 'fail';
      /**
       * Pass or required with value.
       *
       * @type {string}
       */

      this.STATE_NONE = 'none';
      /**
       * Required with no value.
       *
       * @type {string}
       */

      this.STATE_EMPTY = 'empty';
      this.form = element || $;
      this.phoenix = phoenix;
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this.validators = [];
      this.handlers = {};
      this.theme = {};
      this.inputs = this.form.find('input, select, textarea'); // Stop native validation

      if (this.form.length) {
        this.form.attr('novalidate');
      }

      this.registerDefaultValidators();
      this.registerEvents();
    }
    /**
     * Add field.
     *
     * @param {*} input
     * @returns {PhoenixValidationElement}
     */


    _createClass(PhoenixValidationElement, [{
      key: "addField",
      value: function addField(input) {
        this.registerInputEvents(input);
        this.inputs = this.inputs.add(input);
        return this;
      }
    }, {
      key: "removeField",
      value: function removeField(input) {
        this.inputs = this.inputs.filter(function (i, e) {
          return input !== e;
        });
        return this;
      }
      /**
       * Validate All.
       *
       * @returns {boolean}
       */

    }, {
      key: "validateAll",
      value: function validateAll() {
        var _this = this;

        var self = this,
            inValid = [];
        var scroll = self.options.scroll.enabled;
        this.inputs.each(function (i, input) {
          var result = _this.validate(input);

          if (!result) {
            inValid.push(input); // Scroll

            if (scroll) {
              // Find displayed element
              var target = $(input);

              if (!target.is(':visible')) {
                target = target.parents('#' + target.attr('id') + '-control, [data-form-group]');
              }

              $('html, body').animate({
                scrollTop: target.offset().top + self.options.scroll.offset
              }, self.options.scroll.duration);
              scroll = false;
            }
          }
        });
        return inValid.length <= 0;
      }
      /**
       * Validate.
       *
       * @param {jQuery} input
       * @returns {boolean}
       */

    }, {
      key: "validate",
      value: function validate(input) {
        if (!input) {
          return true;
        }

        var $input = $(input);
        var help;
        var result = true;

        if (this.form.length) {
          this.form.addClass('was-validated');
        } // Clear state


        this.showResponse(this.STATE_NONE, $input);
        input.setCustomValidity(''); // Check custom validity

        var validates = ($input.attr('data-validate') || '').split('|');

        if ($input.val() !== '' && validates.length) {
          for (var i in validates) {
            var validator = this.validators[validates[i]];

            if (validator && !validator.handler($input.val(), $input)) {
              help = validator.options.notice;

              if (typeof help === 'function') {
                help = help($input, this);
              } // Set failure value as :invalid


              input.setCustomValidity(this.phoenix.__('phoenix.message.validation.type.mismatch'));
              result = false;
              break;
            }
          }
        } // Check native validity


        if (result) {
          result = input.checkValidity();
        }

        if (result) {
          return true;
        }

        var state = input.validity; // Handle required message.

        if (state.valueMissing) {
          help = $input.attr('data-value-missing-message') || this.phoenix.__('phoenix.message.validation.value.missing');
          this.phoenix.isDebug() ? console.warn("[Debug] Field: ".concat($input.attr('name'), " validity state: value-missing.")) : null;
          this.showResponse(this.STATE_EMPTY, $input, help);
          return false;
        } // Handle types message


        for (var key in state) {
          if (state[key] === true) {
            var type = camelTo(key, '-');
            help = $input.attr('data-' + type + '-message') || this.phoenix.__('phoenix.message.validation.' + camelTo(key, '.'));
            this.phoenix.isDebug() ? console.warn("[Debug] Field: ".concat($input.attr('name'), " validity state: ").concat(type, ".")) : null;
            break;
          }
        }

        this.showResponse(this.STATE_FAIL, $input, help);
        return result;
      }
      /**
       * Show response on input.
       *
       * @param {string}      state
       * @param {jQuery}      $input
       * @param {string|null} help
       *
       * @returns {PhoenixValidationElement}
       */

    }, {
      key: "showResponse",
      value: function showResponse(state, $input) {
        var help = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
        this.phoenix.trigger('validation.response', {
          state: state,
          $input: $input,
          help: help,
          validation: this
        });
        $input.trigger({
          type: 'phoenix.validate.' + state,
          input: $input,
          state: state,
          help: help
        });
        return this;
      }
      /**
       * Remove responses.
       *
       * @param {jQuery} $element
       *
       * @returns {PhoenixValidationElement}
       */

    }, {
      key: "removeResponse",
      value: function removeResponse($element) {
        this.phoenix.trigger('validation.remove', {
          $element: $element
        });
        return this;
      }
      /**
       * Add validator handler.
       *
       * @param name
       * @param validator
       * @param options
       * @returns {PhoenixValidation}
       */

    }, {
      key: "addValidator",
      value: function addValidator(name, validator, options) {
        options = options || {};
        this.validators[name] = {
          handler: validator,
          options: options
        };
        return this;
      }
      /**
       * Register events.
       */

    }, {
      key: "registerEvents",
      value: function registerEvents() {
        var _this2 = this;

        this.form.on('submit', function (event) {
          if (!_this2.validateAll()) {
            event.stopPropagation();
            event.preventDefault();
            return false;
          }

          _this2.form.trigger('phoenix.validate.success');

          return true;
        });
        this.registerInputEvents(this.inputs);
      }
    }, {
      key: "registerInputEvents",
      value: function registerInputEvents($input) {
        var _this3 = this;

        $.each(this.options.events, function (i, event) {
          $input.on(event, function (e) {
            _this3.validate(e.currentTarget);
          });
        });
      }
      /**
       * Register default validators.
       */

    }, {
      key: "registerDefaultValidators",
      value: function registerDefaultValidators() {
        for (var name in handlers) {
          if (handlers.hasOwnProperty(name)) {
            this.addValidator(name, handlers[name]);
          }
        }
      }
    }]);

    return PhoenixValidationElement;
  }();

  function camelTo(str, sep) {
    return str.replace(/([a-z])([A-Z])/g, "$1".concat(sep, "$2")).toLowerCase();
  }

  handlers.username = function (value, element) {
    var regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
    return !regex.test(value);
  };

  handlers.numeric = function (value, element) {
    var regex = /^(\d|-)?(\d|,)*\.?\d*$/;
    return regex.test(value);
  };

  handlers.email = function (value, element) {
    value = punycode.toASCII(value);
    var regex = /^[a-zA-Z0-9.!#$%&â€™*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return regex.test(value);
  };

  handlers.url = function (value, element) {
    value = punycode.toASCII(value);
    var regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
    return regex.test(value);
  };

  handlers.alnum = function (value, element) {
    var regex = /^[a-zA-Z0-9]*$/;
    return regex.test(value);
  };

  handlers.color = function (value, element) {
    var regex = /^#(?:[0-9a-f]{3}){1,2}$/;
    return regex.test(value);
  };
  /**
   * @see  http://www.virtuosimedia.com/dev/php/37-tested-php-perl-and-javascript-regular-expressions
   */


  handlers.creditcard = function (value, element) {
    var regex = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})*$/;
    return regex.test(value);
  };

  handlers.ip = function (value, element) {
    var regex = /^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/;
    return regex.test(value);
  };

  window.PhoenixValidation = PhoenixValidation;
})(jQuery);
//# sourceMappingURL=validation.js.map
