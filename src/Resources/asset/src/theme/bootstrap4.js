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
  class PhoenixUIBootstrap4 extends PhoenixPlugin {
    static get is() { return 'UI'; }

    static get defaultOptions() {
      return {
        messageSelector: '.message-wrap'
      };
    }

    static get proxies() {
      return {
        renderMessage: 'renderMessage'
      };
    }

    ready() {
      super.ready();

      this.messageContainer = $(this.options.messageSelector);
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
        $form.addClass('was-validated');
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
     * @param {string} type
     * @param {string} help
     */
    addValidateResponse($control, $input, icon, type, help) {
      let color;

      color = type === 'success' ? 'valid' : 'invalid';

      $input.addClass('is-' + color);

      if ($control.attr('data-' + type + '-message')) {
        help = $control.attr('data-' + type + '-message');
      }

      if ($input.attr('data-' + type + '-message')) {
        help = $input.attr('data-' + type + '-message');
      }

      if (help) {
        const feedback = '<span class="' + icon + '" aria-hidden="true"></span>';
        const helpElement = $('<small class="' + color + '-feedback form-control-feedback">' + feedback + ' ' + help + '</small>');

        const tagName = $input.prop('tagName').toLowerCase();

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
    removeValidateResponse($element) {
      $element.find('.form-control-feedback').remove();
      $element.find('input')
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

    /**
     * Remove all messages.
     */
    removeMessages() {
      this.messageContainer.children().each(function() {
        this.remove();
      });
    }

    /**
     * Toggle filter bar.
     *
     * @param {jQuery} container
     * @param {jQuery} button
     */
    toggleFilter(container, button) {
      const showClass = button.attr('data-class-show') || 'btn-primary';
      const hideClass = button.attr('data-class-hide') || 'btn-default';

      const icon = button.find('span.filter-button-icon');
      const iconShowClass = icon.attr('data-class-show') || 'fa fa-angle-up';
      const iconHideClass = icon.attr('data-class-hide') || 'fa fa-angle-down';

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
  }

  window.PhoenixUIBootstrap4 = PhoenixUIBootstrap4;

})(jQuery);
