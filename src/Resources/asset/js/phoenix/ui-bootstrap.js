"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function"); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, writable: true, configurable: true } }); if (superClass) _setPrototypeOf(subClass, superClass); }

function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _getPrototypeOf(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _getPrototypeOf(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _possibleConstructorReturn(this, result); }; }

function _possibleConstructorReturn(self, call) { if (call && (_typeof(call) === "object" || typeof call === "function")) { return call; } else if (call !== void 0) { throw new TypeError("Derived constructors may only return object or undefined"); } return _assertThisInitialized(self); }

function _assertThisInitialized(self) { if (self === void 0) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return self; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

function _getPrototypeOf(o) { _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) { return o.__proto__ || Object.getPrototypeOf(o); }; return _getPrototypeOf(o); }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */
(function ($) {
  "use strict";
  /**
   * Bootstrap Theme
   */

  var PhoenixUIBootstrap3 = /*#__PURE__*/function (_PhoenixUI) {
    _inherits(PhoenixUIBootstrap3, _PhoenixUI);

    var _super = _createSuper(PhoenixUIBootstrap3);

    function PhoenixUIBootstrap3() {
      _classCallCheck(this, PhoenixUIBootstrap3);

      return _super.apply(this, arguments);
    }

    _createClass(PhoenixUIBootstrap3, [{
      key: "showValidateResponse",
      value:
      /**
       * Show Validation response.
       *
       * @param {PhoenixValidation} validation
       * @param {string}            state
       * @param {jQuery}            $input
       * @param {string}            help
       */
      function showValidateResponse(validation, state, $input, help) {
        var $control = $input.parents('.form-group').first();
        var self = this;
        this.removeValidateResponse($control);

        if (state != validation.STATE_NONE) {
          var icon, color;

          switch (state) {
            case validation.STATE_SUCCESS:
              color = 'success';
              icon = 'fa fa-check';
              break;

            case validation.STATE_EMPTY:
              color = 'error';
              icon = 'fa fa-remove fa-times';
              break;

            case validation.STATE_FAIL:
              color = 'warning';
              icon = 'fa fa-warning fa-exclamation-triangle';
              break;
          } // Delay 100 to make sure addClass after removeClass


          setTimeout(function () {
            self.addValidateResponse($control, $input, icon, color, help);
          }, 100);
        }
      }
      /**
       * Add validate effect to input, just override this method to fit other templates.
       *
       * @param {jQuery} $control
       * @param {jQuery} $input
       * @param {string} icon
       * @param {string} color
       * @param {string} help
       */

    }, {
      key: "addValidateResponse",
      value: function addValidateResponse($control, $input, icon, color, help) {
        $control.addClass('has-' + color + ' has-feedback');
        var feedback = $('<span class="' + icon + ' form-control-feedback" aria-hidden="true"></span>');
        $control.prepend(feedback);

        if ($control.attr('data-' + color + '-message')) {
          help = $control.attr('data-' + color + '-message');
        }

        if ($input.attr('data-' + color + '-message')) {
          help = $input.attr('data-' + color + '-message');
        }

        if (help) {
          var helpElement = $('<small class="help-block">' + help + '</small>');
          var tagName = $input.prop('tagName').toLowerCase();

          if (tagName === 'div') {
            $input.append(helpElement);
          } else {
            $input.parent().append(helpElement);
          }
        }
      }
      /**
       * Remove validation response.
       *
       * @param {jQuery} $element
       */

    }, {
      key: "removeValidateResponse",
      value: function removeValidateResponse($element) {
        $element.find('.form-control-feedback').remove();
        $element.removeClass('has-error').removeClass('has-success').removeClass('has-warning').removeClass('has-feedback');
        $element.find('.help-block').remove();
      }
      /**
       * Render message.
       *
       * @param {string|Array} msg
       * @param {string}       type
       */

    }, {
      key: "renderMessage",
      value: function renderMessage(msg, type) {
        type = type || 'info';
        var message = this.messageContainer.find('div.alert.alert-' + type);
        var i;

        if (!message.length) {
          message = $('<div class="alert alert-' + type + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></div>');
          this.messageContainer.append(message);
        }

        if (typeof msg === 'string') {
          msg = [msg];
        }

        for (i in msg) {
          message.append('<p>' + msg[i] + '</p>');
        }
      }
    }]);

    return PhoenixUIBootstrap3;
  }(PhoenixUI);

  window.PhoenixUIBootstrap3 = PhoenixUIBootstrap3;
})(jQuery);
//# sourceMappingURL=ui-bootstrap.js.map
