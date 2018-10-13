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
  class PhoenixUIBootstrap3 extends PhoenixUI {
    /**
     * Show Validation response.
     *
     * @param {PhoenixValidation} validation
     * @param {string}            state
     * @param {jQuery}            $input
     * @param {string}            help
     */
    showValidateResponse(validation, state, $input, help) {
      const $control = $input.parents('.form-group').first();
      const self = this;

      this.removeValidateResponse($control);

      if (state != validation.STATE_NONE) {
        let icon, color;

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

      const feedback = $('<span class="' + icon + ' form-control-feedback" aria-hidden="true"></span>');
      $control.prepend(feedback);

      if ($control.attr('data-' + color + '-message')) {
        help = $control.attr('data-' + color + '-message');
      }

      if ($input.attr('data-' + color + '-message')) {
        help = $input.attr('data-' + color + '-message');
      }

      if (help) {
        const helpElement = $('<small class="help-block">' + help + '</small>');

        const tagName = $input.prop('tagName').toLowerCase();

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
     * @param {string|Array} msg
     * @param {string}       type
     */
    renderMessage(msg, type) {
      type = type || 'info';

      let message = this.messageContainer.find('div.alert.alert-' + type);
      let i;

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
  }

  window.PhoenixUIBootstrap3 = PhoenixUIBootstrap3;

})(jQuery);
