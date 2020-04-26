"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * PhoenixMultiSelect
 */
;

(function ($) {
  /**
   * Multi Select.
   *
   * @param {jQuery} $element
   * @param {Object} options
   *
   * @constructor
   */
  var PhoenixMultiSelect = /*#__PURE__*/function () {
    _createClass(PhoenixMultiSelect, null, [{
      key: "pluginName",
      get: function get() {
        return 'multiselect';
      }
    }, {
      key: "defaultOptions",
      get: function get() {
        return {
          duration: 100,
          inputSelector: 'input.grid-checkbox[type=checkbox]'
        };
      }
    }]);

    function PhoenixMultiSelect($element, options) {
      _classCallCheck(this, PhoenixMultiSelect);

      var self = this;
      this.options = $.extend({}, this.constructor.defaultOptions, options);
      this.form = $element;
      this.boxes = $element.find(this.options.inputSelector);
      this.last = false;
      this.boxes.parent().css('user-select', 'none');
      this.boxes.on('click', function (event) {
        self.select(this, event);
      });
    }
    /**
     * Do select.
     *
     * @param {Element} element
     * @param {Event}   event
     */


    _createClass(PhoenixMultiSelect, [{
      key: "select",
      value: function select(element, event) {
        if (!this.last) {
          this.last = element;
          return;
        }

        if (event.shiftKey) {
          var self = this;
          var start = this.boxes.index(element);
          var end = this.boxes.index(this.last);
          var chs = this.boxes.slice(Math.min(start, end), Math.max(start, end) + 1);
          $.each(chs, function (i, e) {
            if (self.options.duration) {
              setTimeout(function () {
                e.checked = self.last.checked;
              }, self.options.duration / chs.length * i);
            } else {
              e.checked = self.last.checked;
            }
          });
        }

        this.last = element;
      }
    }]);

    return PhoenixMultiSelect;
  }();

  $.fn[PhoenixMultiSelect.pluginName] = function (options) {
    if (!this.data('phoenix.' + PhoenixMultiSelect.pluginName)) {
      this.data('phoenix.' + PhoenixMultiSelect.pluginName, new PhoenixMultiSelect(this, options));
    }

    return this.data('phoenix.' + PhoenixMultiSelect.pluginName);
  };
})(jQuery);
//# sourceMappingURL=multiselect.js.map
