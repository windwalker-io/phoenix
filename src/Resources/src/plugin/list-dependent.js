/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

;(function($) {
  "use strict";

  const nope = (value, ele, dep) => {};

  /**
   * Class init.
   * @param {jQuery}        $element
   * @param {jQuery|string} dependent
   * @param {Object}        options
   * @constructor
   */
  class ListDependent {
    static get pluginName() { return 'listDependent' }
    static get defaultOptions() {
      return {
        ajax: {
          url: null,
          value_field: 'value'
        },
        source: null,
        text_field: 'title',
        value_field: 'id',
        first_option: null,
        default_value: null,
        initial_load: true,
        empty_mark: '__EMPTY__',
        hooks: {
          before_request: nope,
          after_request: nope
        }
      };
    }

    constructor($element, dependent, options) {
      this.element = $element;
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this.dependent = $(dependent);

      this.bindEvents();

      if (this.options.initial_load) {
        this.changeList(this.dependent.val(), true);
      }
    }

    /**
     * Bind events.
     */
    bindEvents() {
      this.dependent.on('change', (event) => {
        this.changeList($(event.currentTarget).val());
      });
    }

    /**
     * Update the list elements.
     *
     * @param {*}    value
     * @param {bool} initial
     */
    changeList(value, initial = null) {
      value = value || this.dependent.val();

      // Empty mark
      if (value === '') {
        value = this.options.empty_mark;
      }

      if (this.options.ajax.url) {
        this.ajaxUpdate(value);
      } else if (this.options.source) {
        this.sourceUpdate(value, initial);
      }
    }

    /**
     * Update list by source.
     *
     * @param {string} value
     * @param {bool}   initial
     */
    sourceUpdate(value, initial = null) {
      const source = this.options.source;

      this.beforeHook(value, this.element, this.dependent);

      if (source[value]) {
        this.updateListElements(source[value]);
      } else {
        this.updateListElements([]);

        if (!initial && value !== '' && parseInt(value) !== 0) {
          console.log('List for value: ' + value + ' not found.');
        }
      }

      this.afterHook(value, this.element, this.dependent);
    }

    /**
     * Do ajax.
     *
     * @param {string} value
     */
    ajaxUpdate(value) {
      const data = {};
      data[this.options.ajax.value_field] = value;

      this.beforeHook(value, this.element, this.dependent);

      $.get(this.options.ajax.url, data)
        .done(response => {
          if (response.success) {
            this.updateListElements(response.data);
          } else {
            console.error(response.message);
          }
        }).fail(response => {
        console.error(response.message);
      }).always(() => {
        this.afterHook(value, this.element, this.dependent);
      });
    }

    /**
     * Update list elements.
     *
     * @param {Array} items
     */
    updateListElements(items) {
      const self = this;
      const textField = this.options.text_field;
      const valueField = this.options.value_field;
      self.element.empty();

      if (this.options.first_option) {
        items.unshift({});
        items[0][textField] = this.options.first_option[textField];
        items[0][valueField] = this.options.first_option[valueField];
      }

      $.each(items, function() {
        const value = this[valueField];
        const option = $('<option>' + this[textField] + '</option>');
        option.attr('value', value);

        if (this.attributes) {
          $.each(this.attributes, function(index) {
            option.attr(index, this);
          });
        }

        if (self.options.default_value == value) {
          option.attr('selected', 'selected');
        }

        self.element.append(option);
      });

      self.element.trigger('chosen:updated');
      self.element.trigger('change');
    }

    /**
     * Before hook.
     *
     * @param {string} value
     * @param {jQuery} element
     * @param {jQuery} dependent
     * @returns {*}
     */
    beforeHook(value, element, dependent) {
      const before = this.options.hooks.before_request;

      return before.call(this, value, element, dependent);
    }

    /**
     * After hook.
     *
     * @param {string} value
     * @param {jQuery} element
     * @param {jQuery} dependent
     * @returns {*}
     */
    afterHook(value, element, dependent) {
      const after = this.options.hooks.after_request;

      return after.call(this, value, element, dependent);
    }
  }

  /**
   * Push plugins.
   *
   * @param {jQuery} dependent
   * @param {Object} options
   *
   * @returns {*}
   */
  $.fn[ListDependent.pluginName] = function(dependent, options) {
    if (!this.data("phoenix." + ListDependent.pluginName)) {
      this.data("phoenix." + ListDependent.pluginName, new ListDependent(this, dependent, options));
    }

    return this.data("phoenix." + ListDependent.pluginName);
  };
})(jQuery);
