'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

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

  var PhoenixUIBootstrap3 = function (_PhoenixUI) {
    _inherits(PhoenixUIBootstrap3, _PhoenixUI);

    function PhoenixUIBootstrap3() {
      _classCallCheck(this, PhoenixUIBootstrap3);

      return _possibleConstructorReturn(this, (PhoenixUIBootstrap3.__proto__ || Object.getPrototypeOf(PhoenixUIBootstrap3)).apply(this, arguments));
    }

    _createClass(PhoenixUIBootstrap3, [{
      key: 'showValidateResponse',

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
              icon = 'fa fa-warning';
              break;
          }

          // Delay 100 to make sure addClass after removeClass
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
      key: 'addValidateResponse',
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
      key: 'removeValidateResponse',
      value: function removeValidateResponse($element) {
        $element.find('.form-control-feedback').remove();
        $element.removeClass('has-error').removeClass('has-success').removeClass('has-warning').removeClass('has-feedback');

        $element.find('.help-block').remove();
      }

      /**
       * Render message.
       *
       * @param {jQuery}       messageContainer
       * @param {string|Array} msg
       * @param {string}       type
       */

    }, {
      key: 'renderMessage',
      value: function renderMessage(messageContainer, msg, type) {
        type = type || 'info';

        var message = messageContainer.find('div.alert.alert-' + type),
            i;

        if (!message.length) {
          message = $('<div class="alert alert-' + type + '"><button type="button" class="close" data-dismiss="alert" aria-label="Close"></div>');
          messageContainer.append(message);
        }

        if (typeof msg === 'string') {
          msg = [msg];
        }

        for (i in msg) {
          message.append('<p>' + msg[i] + '</p>');
        }
      }

      /**
       * Remove all messages.
       *
       * @param {jQuery} messageContainer
       */

    }, {
      key: 'removeMessages',
      value: function removeMessages(messageContainer) {
        var messages = messageContainer.children();

        messages.each(function () {
          this.remove();
        });
      }
    }]);

    return PhoenixUIBootstrap3;
  }(PhoenixUI);

  window.PhoenixUIBootstrap3 = PhoenixUIBootstrap3;
})(jQuery);
//# sourceMappingURL=ui-bootstrap.js.map
