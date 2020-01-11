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
  class PhoenixUIBootstrap4 extends PhoenixUI {
    static get defaultOptions() {
      return {
        messageSelector: '.message-wrap',
        feedbackContainer: 'tooltip'
      };
    }

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
      const $form = $input.parents('form');
      const self = this;

      // Add class to form
      if (!$form.hasClass('was-validated')) {
        // $form.addClass('was-validated');
      }

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
     * @param {string} type
     * @param {string} help
     */
    addValidateResponse($control, $input, icon, type, help) {
      let color;

      color = type === 'success' ? 'valid' : 'invalid';

      $control.addClass(`has-${color}`);
      $control.find('.form-control')
        .addClass(`is-${color}`);
      $control.find('.form-check-input')
        .addClass(`is-${color}`);

      if ($control.attr(`data-${type}-message`)) {
        help = $control.attr(`data-${type}-message`);
      }

      if ($input.attr(`data-${type}-message`)) {
        help = $input.attr(`data-${type}-message`);
      }

      if (help) {
        const feedback = `<span class="${icon}" aria-hidden="true"></span>`;
        const helpElement = $(`<small class="${color}-${this.options.feedbackContainer} form-control-${this.options.feedbackContainer}">${feedback} ${help}</small>`);

        if (color === 'invalid' && !$control.is(':visible')) {
          this.renderMessage(`<strong>${$control.find('label').text()}:</strong> ${help}`, 'warning');
        } else {
          const tagName = $input.prop('tagName').toLowerCase();

          if (tagName === 'div') {
            $input.append(helpElement);
          } else {
            $input.parent().append(helpElement);
          }
        }
      }

      // End method
    }

    /**
     * Remove validation response.
     *
     * @param {jQuery} $element
     */
    removeValidateResponse($element) {
      $element.find(`.form-control-${this.options.feedbackContainer}`).remove();
      $element
        .removeClass('has-invalid')
        .removeClass('has-valid');
      $element.find('.form-control')
        .removeClass('is-invalid')
        .removeClass('is-valid');
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

  window.PhoenixUIBootstrap4 = PhoenixUIBootstrap4;

})(jQuery);
