/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

var Phoenix;
(function(Phoenix, $) {
  "use strict";

  /**
   * Bootstrap Theme
   */
  Phoenix.Theme = {
    /**
     * Show Validation response.
     *
     * @param {PhoenixValidation} validation
     * @param {string}            state
     * @param {jQuery}            $input
     * @param {string}            help
     */
    showValidateResponse: function(validation, state, $input, help) {
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
    },

    /**
     * Add validate effect to input, just override this method to fit other templates.
     *
     * @param {jQuery} $control
     * @param {jQuery} $input
     * @param {string} icon
     * @param {string} type
     * @param {string} help
     */
    addValidateResponse: function($control, $input, icon, type, help) {
      var color;

      color = type === 'success' ? 'valid' : 'invalid';

      $input.addClass('is-' + color);

      if ($control.attr('data-' + type + '-message')) {
        help = $control.attr('data-' + type + '-message');
      }

      if ($input.attr('data-' + type + '-message')) {
        help = $input.attr('data-' + type + '-message');
      }

      if (help) {
        var feedback = '<span class="' + icon + '" aria-hidden="true"></span>';
        var helpElement = $('<small class="' + color + '-feedback form-control-feedback">' + feedback + ' ' + help + '</small>');

        var tagName = $input.prop('tagName').toLowerCase();

        if (tagName === 'div') {
          $input.append(helpElement);
        }
        else {
          $input.parent().append(helpElement);
        }
      }
    },

    /**
     * Remove validation response.
     *
     * @param {jQuery} $element
     */
    removeValidateResponse: function($element) {
      $element.find('.form-control-feedback').remove();
      $element.find('input')
        .removeClass('is-invalid')
        .removeClass('is-valid');
    },

    /**
     * Render message.
     *
     * @param {jQuery}       messageContainer
     * @param {string|Array} msg
     * @param {string}       type
     */
    renderMessage: function(messageContainer, msg, type) {
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
    },

    /**
     * Remove all messages.
     *
     * @param {jQuery} messageContainer
     */
    removeMessages: function(messageContainer) {
      var messages = messageContainer.children();

      messages.each(function() {
        this.remove();
      });
    },

    /**
     * Toggle filter bar.
     *
     * @param {jQuery} container
     * @param {jQuery} button
     */
    toggleFilter: function(container, button) {
      var showClass = button.attr('data-class-show') || 'btn-primary';
      var hideClass = button.attr('data-class-hide') || 'btn-default';

      var icon = button.find('span.filter-button-icon');
      var iconShowClass = icon.attr('data-class-show') || 'fa fa-angle-up';
      var iconHideClass = icon.attr('data-class-hide') || 'fa fa-angle-down';

      if (container.hasClass('shown')) {
        button.removeClass(showClass).addClass(hideClass);
        container.hide('fast');
        container.removeClass('shown');

        icon.removeClass(iconShowClass).addClass(iconHideClass);
      }
      else {
        button.removeClass(hideClass).addClass(showClass);
        container.show('fast');
        container.addClass('shown');

        icon.removeClass(iconHideClass).addClass(iconShowClass);
      }
    }
  };

})(Phoenix || (Phoenix = {}), jQuery);
