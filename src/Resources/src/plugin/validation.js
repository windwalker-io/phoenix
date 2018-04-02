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
      this.inputs = this.form.find('input, select, textarea, div.input-list-container');

      this.registerDefaultValidators();
      this.registerEvents();
    }

    /**
     * Add field.
     *
     * @param {*} input
     * @returns {PhoenixValidationElement}
     */
    addField(input) {
      this.inputs = this.inputs.add(input);

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

      this.inputs.each(function() {
        if (!self.validate(this)) {
          inValid.push(this);

          // Scroll
          if (scroll) {
            // Find displayed element
            let target = $(this);

            if (!target.is(':visible')) {
              target = target.parents('#' + target.attr('id') + '-control');
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
      let $input = $(input), className, validator;

      if ($input.attr('disabled')) {
        this.showResponse(this.STATE_NONE, $input);

        return true;
      }


      if ($input.attr('required') || $input.hasClass('required')) {
        // Single Radio & Checkbox
        if (($input.attr('type') === 'radio' || $input.attr('type') === 'checkbox') && !$input.is(':checked')) {
          this.showResponse(this.STATE_EMPTY, $input);
          return false;
        } else if ($input.prop("tagName").toLowerCase() === 'div' && $input.hasClass('input-list-container')) {
          // Input List (Radios & Checkboxes)
          if (!$input.find('input:checked').length) {
            // Set as :invalid
            $input.find('input').each(function () {
              this.setCustomValidity('Please select at least one.');
            });

            this.showResponse(this.STATE_EMPTY, $input);

            return false;
          } else {
            // Set as :valid
            $input.find('input').each(function () {
              this.setCustomValidity('');
            });

            this.showResponse(this.STATE_SUCCESS, $input);
          }
        }

        // Handle all fields and checkbox
        else if (!$input.val() || (Array.isArray($input.val()) && $input.val().length === 0)) {
          this.showResponse(this.STATE_EMPTY, $input);

          return false;
        }
      }

      // Is value exists, validate this type.
      className = $input.attr('class');

      if (className) {
        validator = className.match(/validate-([a-zA-Z0-9_|-]+)/);
      }

      // Empty value and no validator config, set response to none.
      if (!$input.val() || !validator) {
        this.showResponse(this.STATE_NONE, $input);

        return true;
      }

      validator = this.validators[validator[1]];

      if (!validator || !validator.handler) {
        this.showResponse(this.STATE_SUCCESS, $input);

        return true;
      }

      if (!validator.handler($input.val(), $input)) {
        let help = validator.options.notice;

        if (typeof  help === 'function') {
          help = help($input, this);
        }

        // Set failure value as :invalid
        $input[0].setCustomValidity('Input is invalid.');
        this.showResponse(this.STATE_FAIL, $input, help);

        return false;
      }

      this.showResponse(this.STATE_SUCCESS, $input);

      return true;
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
        type: 'phoenix.validate.' + state,
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
      const self = this;

      this.form.on('submit', event => {
        if (!this.validateAll()) {
          event.stopPropagation();
          event.preventDefault();

          return false;
        }

        this.form.trigger('phoenix.validate.success');

        return true;
      });

      $.each(this.options.events, function() {
        self.inputs.on(this, function() {
          self.validate(this);
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

  handlers.username = function(value, element) {
    const regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
    return !regex.test(value);
  };

  handlers.password = function(value, element) {
    const regex = /^\S[\S ]{2,98}\S$/;
    return regex.test(value);
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
    value = punycode.toASCII(value);
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

  window.PhoenixValidation = PhoenixValidation;

})(jQuery);
