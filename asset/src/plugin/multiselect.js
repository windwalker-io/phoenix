/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * PhoenixMultiSelect
 */
;(function($) {
  /**
   * Multi Select.
   *
   * @param {jQuery} $element
   * @param {Object} options
   *
   * @constructor
   */
  class PhoenixMultiSelect {
    static get pluginName() { return 'multiselect' }
    static get defaultOptions() {
      return {
        duration: 100,
        inputSelector: 'input.grid-checkbox[type=checkbox]'
      }
    }

    constructor($element, options) {
      const self = this;
      this.options = $.extend({}, this.constructor.defaultOptions, options);
      this.form = $element;
      this.boxes = $element.find(this.options.inputSelector);
      this.last = false;

      this.boxes.parent().css('user-select', 'none');

      this.boxes.on('click', function(event) {
        self.select(this, event);
      });
    }

    /**
     * Do select.
     *
     * @param {Element} element
     * @param {Event}   event
     */
    select(element, event) {
      if (!this.last) {
        this.last = element;

        return;
      }

      if (event.shiftKey) {
        const self = this;
        const start = this.boxes.index(element);
        const end = this.boxes.index(this.last);

        const chs = this.boxes.slice(Math.min(start, end), Math.max(start, end) + 1);

        $.each(chs, function(i, e) {
          if (self.options.duration) {
            setTimeout(function() {
              e.checked = self.last.checked;
            }, (self.options.duration / chs.length) * i);
          }
          else {
            e.checked = self.last.checked;
          }
        })
      }

      this.last = element;
    }
  }

  $.fn[PhoenixMultiSelect.pluginName] = function(options) {
    if (!this.data('phoenix.' + PhoenixMultiSelect.pluginName)) {
      this.data('phoenix.' + PhoenixMultiSelect.pluginName, new PhoenixMultiSelect(this, options));
    }

    return this.data('phoenix.' + PhoenixMultiSelect.pluginName);
  };

})(jQuery);
