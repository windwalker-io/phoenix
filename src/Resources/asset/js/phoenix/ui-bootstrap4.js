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
 * @license    GNU General Public License version 2 or later.
 */
(function ($) {
  "use strict";
  /**
   * Bootstrap Theme
   */

  var PhoenixUIBootstrap4 =
  /*#__PURE__*/
  function (_PhoenixUI) {
    _inherits(PhoenixUIBootstrap4, _PhoenixUI);

    function PhoenixUIBootstrap4() {
      _classCallCheck(this, PhoenixUIBootstrap4);

      return _possibleConstructorReturn(this, _getPrototypeOf(PhoenixUIBootstrap4).apply(this, arguments));
    }

    _createClass(PhoenixUIBootstrap4, [{
      key: "showValidateResponse",

      /**
       * Show Validation response.
       *
       * @param {PhoenixValidation} validation
       * @param {string}            state
       * @param {jQuery}            $input
       * @param {string}            help
       */
      value: function showValidateResponse(validation, state, $input, help) {
        var $control = $input.parents('.form-group').first();
        var $form = $input.parents('form');
        var self = this; // Add class to form

        if (!$form.hasClass('was-validated')) {
          $form.addClass('was-validated');
        }

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
       * @param {string} type
       * @param {string} help
       */

    }, {
      key: "addValidateResponse",
      value: function addValidateResponse($control, $input, icon, type, help) {
        var color;
        color = type === 'success' ? 'valid' : 'invalid';
        $control.addClass("has-".concat(color));
        $control.find('.form-control').addClass("is-".concat(color));
        $control.find('.form-check-input').addClass("is-".concat(color));

        if ($control.attr("data-".concat(type, "-message"))) {
          help = $control.attr("data-".concat(type, "-message"));
        }

        if ($input.attr("data-".concat(type, "-message"))) {
          help = $input.attr("data-".concat(type, "-message"));
        }

        if (help) {
          var feedback = "<span class=\"".concat(icon, "\" aria-hidden=\"true\"></span>");
          var helpElement = $("<small class=\"".concat(color, "-").concat(this.options.feedbackContainer, " form-control-").concat(this.options.feedbackContainer, "\">").concat(feedback, " ").concat(help, "</small>"));
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
        $element.find(".form-control-".concat(this.options.feedbackContainer)).remove();
        $element.removeClass('has-invalid').removeClass('has-valid');
        $element.find('.form-control').removeClass('is-invalid').removeClass('is-valid');
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
    }], [{
      key: "defaultOptions",
      get: function get() {
        return {
          messageSelector: '.message-wrap',
          feedbackContainer: 'tooltip'
        };
      }
    }]);

    return PhoenixUIBootstrap4;
  }(PhoenixUI);

  window.PhoenixUIBootstrap4 = PhoenixUIBootstrap4;
})(jQuery);
//# sourceMappingURL=ui-bootstrap4.js.map
