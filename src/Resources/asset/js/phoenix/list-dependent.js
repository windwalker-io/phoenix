"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */
;

(function ($) {
  "use strict";

  var nope = function nope(value, ele, dep) {};
  /**
   * Class init.
   * @param {jQuery}        $element
   * @param {jQuery|string} dependent
   * @param {Object}        options
   * @constructor
   */


  var ListDependent = /*#__PURE__*/function () {
    function ListDependent($element, dependent, options) {
      _classCallCheck(this, ListDependent);

      _defineProperty(this, "$xhr", null);

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


    _createClass(ListDependent, [{
      key: "bindEvents",
      value: function bindEvents() {
        var _this = this;

        this.dependent.on('change', function (event) {
          _this.changeList($(event.currentTarget).val());
        });
      }
      /**
       * Update the list elements.
       *
       * @param {*}    value
       * @param {bool} initial
       */

    }, {
      key: "changeList",
      value: function changeList(value) {
        var initial = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
        value = value || this.dependent.val(); // Empty mark

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

    }, {
      key: "sourceUpdate",
      value: function sourceUpdate(value) {
        var initial = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
        var source = this.options.source;
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

    }, {
      key: "ajaxUpdate",
      value: function ajaxUpdate(value) {
        var _this2 = this;

        var data = {};
        data[this.options.ajax.value_field] = value;
        this.beforeHook(value, this.element, this.dependent);

        if (this.$xhr) {
          this.$xhr.abort();
          this.$xhr = null;
        }

        this.$xhr = Phoenix.Ajax.get(this.options.ajax.url, data).done(function (response) {
          if (response.success) {
            _this2.updateListElements(response.data);
          } else {
            console.error(response.message);
          }
        }).fail(function (err) {
          console.error(err);
        }).always(function () {
          _this2.afterHook(value, _this2.element, _this2.dependent);

          _this2.$xhr = null;
        });
      }
      /**
       * Update list elements.
       *
       * @param {Array} items
       */

    }, {
      key: "updateListElements",
      value: function updateListElements(items) {
        var _this3 = this;

        var self = this;
        var textField = this.options.text_field;
        var valueField = this.options.value_field;
        self.element.empty();

        if (this.options.first_option) {
          items.unshift({});
          items[0][textField] = this.options.first_option[textField];
          items[0][valueField] = this.options.first_option[valueField];
        }

        $.each(items, function (i, item) {
          if (Array.isArray(item)) {
            var group = $("<optgroup label=\"".concat(i, "\"></optgroup>"));
            $.each(item, function (k, child) {
              _this3.appendOptionTo({
                value: child[valueField],
                text: child[textField],
                attributes: child.attributes
              }, group);
            });

            _this3.element.append(group);

            return;
          }

          _this3.appendOptionTo({
            value: item[valueField],
            text: item[textField],
            attributes: item.attributes
          }, _this3.element);
        });
        this.element.trigger('chosen:updated');
        this.element.trigger('change');
      }
    }, {
      key: "appendOptionTo",
      value: function appendOptionTo(item, parent) {
        var value = item.value;
        var option = $('<option>' + item.text + '</option>');
        option.attr('value', value);

        if (item.attributes) {
          $.each(item.attributes, function (index, val) {
            option.attr(index, val);
          });
        }

        if (this.isSelected(value)) {
          option.attr('selected', 'selected');
        }

        parent.append(option);
      }
    }, {
      key: "isSelected",
      value: function isSelected(value) {
        var defaultValues = ''; // Convert all types to array

        if (Array.isArray(this.options.default_value)) {
          defaultValues = this.options.default_value;
        } else if (this.options.default_value && _typeof(this.options.default_value) === 'object') {
          defaultValues = Object.keys(this.options.default_value);
        } else {
          defaultValues = [this.options.default_value];
        }

        return defaultValues.indexOf(value) !== -1;
      }
      /**
       * Before hook.
       *
       * @param {string} value
       * @param {jQuery} element
       * @param {jQuery} dependent
       * @returns {*}
       */

    }, {
      key: "beforeHook",
      value: function beforeHook(value, element, dependent) {
        var before = this.options.hooks.before_request;
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

    }, {
      key: "afterHook",
      value: function afterHook(value, element, dependent) {
        var after = this.options.hooks.after_request;
        return after.call(this, value, element, dependent);
      }
    }], [{
      key: "pluginName",
      get: function get() {
        return 'listDependent';
      }
    }, {
      key: "defaultOptions",
      get: function get() {
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
    }]);

    return ListDependent;
  }();
  /**
   * Push plugins.
   *
   * @param {jQuery} dependent
   * @param {Object} options
   *
   * @returns {*}
   */


  $.fn[ListDependent.pluginName] = function (dependent, options) {
    if (!this.data("phoenix." + ListDependent.pluginName)) {
      this.data("phoenix." + ListDependent.pluginName, new ListDependent(this, dependent, options));
    }

    return this.data("phoenix." + ListDependent.pluginName);
  };
})(jQuery);
//# sourceMappingURL=list-dependent.js.map
