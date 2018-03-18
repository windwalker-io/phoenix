/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(($) => {
  class PhoenixUI extends PhoenixPlugin {
    static get is() { return 'UI'; }

    static get defaultOptions() {
      return {
        messageSelector: '.message-wrap'
      };
    }

    static get proxies() {
      return {
        addMessage: 'renderMessage'
      };
    }

    constructor() {
      super();

      this.aliveHandle = null;
    }

    ready() {
      super.ready();

      this.messageContainer = $(this.options.messageSelector);

      this.phoenix.on('validation.response', event => {
        this.showValidateResponse(event.validation, event.state, event.$input, event.help);
      });

      this.phoenix.on('validation.remove', event => {
        this.removeValidateResponse(event.$element);
      });
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
      throw new Error('Please implement this method.');
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
      throw new Error('Please implement this method.');
    }

    /**
     * Remove validation response.
     *
     * @param {jQuery} $element
     */
    removeValidateResponse($element) {
      throw new Error('Please implement this method.');
    }

    /**
     * Render message.
     *
     * @param {string|Array} msg
     * @param {string}       type
     */
    renderMessage(msg, type) {
      throw new Error('Please implement this method.');
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

    /**
     * Confirm popup.
     *
     * TODO: Move to core.
     *
     * @param {string}   message
     * @param {Function} callback
     */
    confirm(message, callback) {
      message = message || 'Are you sure?';

      const confirmed = confirm(message);

      callback(confirmed);

      return confirmed;
    }

    /**
     * Keep alive.
     *
     * @param {string} url
     * @param {Number} time
     *
     * @return {number}
     */
    keepAlive(url, time) {
      return this.aliveHandle = window.setInterval(() => $.get('/'), time);
    }

    stopKeepAlive() {
      clearInterval(this.aliveHandle);
    }
  }

  window.PhoenixUI = PhoenixUI;
})(jQuery);
