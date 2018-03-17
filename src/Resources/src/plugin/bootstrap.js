/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

(($) => {
  "use strict";

  /**
   * Bootstrap Theme
   */
  class PhoenixUIBootstrap3 {
    /**
     * Show Validation response.
     *
     * @param {PhoenixValidation} validation
     * @param {string}            state
     * @param {jQuery}            $input
     * @param {string}            help
     */
    showValidateResponse(validation, state, $input, help) {
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
        setTimeout(function() {
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
    addValidateResponse($control, $input, icon, color, help) {
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
        }
        else {
          $input.parent().append(helpElement);
        }
      }
    }

    /**
     * Remove validation response.
     *
     * @param {jQuery} $element
     */
    removeValidateResponse($element) {
      $element.find('.form-control-feedback').remove();
      $element.removeClass('has-error')
        .removeClass('has-success')
        .removeClass('has-warning')
        .removeClass('has-feedback');

      $element.find('.help-block').remove();
    }

    /**
     * Render message.
     *
     * @param {jQuery}       messageContainer
     * @param {string|Array} msg
     * @param {string}       type
     */
    renderMessage(messageContainer, msg, type) {
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
    removeMessages(messageContainer) {
      var messages = messageContainer.children();

      messages.each(function() {
        this.remove();
      });
    }
  }

  window.PhoenixUIBootstrap3 = PhoenixUIBootstrap3;

})(jQuery);
