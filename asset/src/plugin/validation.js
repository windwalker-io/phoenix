/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

/**
 * PhoenixValidation
 */
;(function($) {
  "use strict";

  /**
   * Default handlers
   *
   * @type {Object}
   */
  const handlers = {};

  /**
   * Class init.
   *
   * @param {jQuery} element
   * @param {Object} options
   * @constructor
   */
  class PhoenixValidation extends PhoenixJQueryPlugin {
    static get is() { return 'Validation' }

    static get pluginName() { return 'validation' }

    static get pluginClass() { return PhoenixValidationElement }

    static get proxies() {
      return {
        validation: 'createPlugin'
      };
    }
  }

  class PhoenixValidationElement {
    static get defaultOptions() {
      return {
        enabled: true,
        events: ['change'],
        scroll: {
          enabled: true,
          offset: -100,
          duration: 1000
        }
      }
    }

    constructor(element, options, phoenix) {
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
      this.inputs = [];

      // Stop native validation
      if (this.form.length) {
        this.form.attr('novalidate', true);
      }

      this.registerDefaultValidators();
      this.registerEvents();
    }

    getInputs() {
      const inputs = this.form.find('input, select, textarea');

      this.inputs.forEach(input => inputs.add(input));

      return inputs;
    }

    /**
     * Add field.
     *
     * @param {*} input
     * @returns {PhoenixValidationElement}
     */
    addField(input) {
      this.registerInputEvents(input);

      this.inputs.push(input);

      return this;
    }

    removeField(input) {
      this.inputs = this.inputs.filter((i, e) => input !== e);

      return this;
    }

    /**
     * Validate All.
     *
     * @returns {boolean}
     */
    validateAll() {
      const self = this, inValid = [];
      let scroll = self.options.scroll.enabled;

      if (this.form.length) {
        this.form.addClass('was-validated');
      }

      this.getInputs().each((i, input) => {
        const result = this.validate(input);

        if (!result) {
          inValid.push(input);

          // Scroll
          if (scroll) {
            // Find displayed element
            let target = $(input);

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
    validate(input) {
      if (!input) {
        return true;
      }

      let $input = $(input);
      let help;
      let result = true;

      // Clear state
      this.showResponse(this.STATE_NONE, $input);
      input.setCustomValidity('');

      // Check custom validity
      const validates = ($input.attr('data-validate') || '').split('|');

      if ($input.val() !== '' && validates.length) {
        for (let i in validates) {
          const validator = this.validators[validates[i]];

          if (validator && !validator.handler($input.val(), $input)) {
            help = validator.options.notice;

            if (typeof  help === 'function') {
              help = help($input, this);
            }

            // Set failure value as :invalid
            input.setCustomValidity(this.phoenix.__('phoenix.message.validation.type.mismatch'));

            result = false;

            break;
          }
        }
      }

      // Check native validity
      if (result) {
        result = input.checkValidity();
        this.showResponse(this.STATE_SUCCESS, $input, help);
      }

      if (result) {
        return true;
      }

      const state = input.validity;

      // Handle required message.
      if (state.valueMissing) {
        help = $input.attr('data-value-missing-message') || this.phoenix.__('phoenix.message.validation.value.missing');
        this.phoenix.isDebug() ? console.warn(`[Debug] Field: ${$input.attr('name')} validity state: value-missing.`) : null;
        this.showResponse(this.STATE_EMPTY, $input, help);
        return false;
      }

      // Handle types message
      for (let key in state) {
        if (state[key] === true) {
          const type = camelTo(key, '-');

          help = $input.attr('data-' + type + '-message') || this.phoenix.__('phoenix.message.validation.' + camelTo(key, '.'));

          this.phoenix.isDebug() ? console.warn(`[Debug] Field: ${$input.attr('name')} validity state: ${type}.`) : null;

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
    showResponse(state, $input, help = null) {
      this.phoenix.trigger('validation.response', {state, $input, help, validation: this});

      $input.trigger({
        type: 'phoenix.validate.input.' + state,
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
    removeResponse($element) {
      this.phoenix.trigger('validation.remove', {$element});

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
    addValidator(name, validator, options) {
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
    registerEvents() {
      this.form.on('submit', event => {
        if (this.options.enabled && !this.validateAll()) {
          event.stopImmediatePropagation(); // Stop following events
          event.stopPropagation();
          event.preventDefault();

          return false;
        }

        this.form.trigger('phoenix.validate.success');

        return true;
      });

      this.registerInputEvents(this.getInputs());
    }

    registerInputEvents($input) {
      $.each(this.options.events, (i, event) => {
        $input.on(event, (e) => {
          this.validate(e.currentTarget);
        });
      });
    }

    /**
     * Register default validators.
     */
    registerDefaultValidators() {
      for (let name in handlers) {
        if (handlers.hasOwnProperty(name)) {
          this.addValidator(name, handlers[name]);
        }
      }
    }
  }

  function camelTo(str, sep) {
    return str.replace(/([a-z])([A-Z])/g, `$1${sep}$2`).toLowerCase();
  }

  handlers.username = function(value, element) {
    const regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
    return !regex.test(value);
  };

  handlers.numeric = function(value, element) {
    const regex = /^(\d|-)?(\d|,)*\.?\d*$/;
    return regex.test(value);
  };

  handlers.email = function(value, element) {
    value = punycode.toASCII(value);
    const regex = /^[a-zA-Z0-9.!#$%&â€™*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return regex.test(value);
  };

  handlers.url = function(value, element) {
    const regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
    return regex.test(value);
  };

  handlers.alnum = function(value, element) {
    const regex = /^[a-zA-Z0-9]*$/;
    return regex.test(value);
  };

  handlers.color = function(value, element) {
    const regex = /^#(?:[0-9a-f]{3}){1,2}$/;
    return regex.test(value);
  };

  /**
   * @see  http://www.virtuosimedia.com/dev/php/37-tested-php-perl-and-javascript-regular-expressions
   */
  handlers.creditcard = function(value, element) {
    const regex = /^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6011[0-9]{12}|622((12[6-9]|1[3-9][0-9])|([2-8][0-9][0-9])|(9(([0-1][0-9])|(2[0-5]))))[0-9]{10}|64[4-9][0-9]{13}|65[0-9]{14}|3(?:0[0-5]|[68][0-9])[0-9]{11}|3[47][0-9]{13})*$/;
    return regex.test(value);
  };

  handlers.ip = function(value, element) {
    const regex = /^((?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?))*$/;
    return regex.test(value);
  };

  handlers['password-confirm'] = function (value, element) {
    const selector = element.attr('data-confirm-target');
    const target = $(selector);

    return target.val() === value;
  };

  window.PhoenixValidation = PhoenixValidation;

})(jQuery);
