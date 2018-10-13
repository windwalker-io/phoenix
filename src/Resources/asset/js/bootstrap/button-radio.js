'use strict';

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

(function ($) {
  "use strict";

  var plugin = 'buttonRadio';

  var defaultOptions = {
    selector: '.btn-group .radio',
    buttonClass: 'btn',
    activeClass: 'active',
    color: {
      'default': 'btn-default btn-outline-secondary',
      green: 'btn-success',
      red: 'btn-danger',
      blue: 'btn-primary'
    }
  };

  /**
   * Button Radio
   *
   * @param {HTMLElement} element
   * @param {Object}      options
   *
   * @constructor
   */

  var ButtonRadio = function ButtonRadio(element, options) {
    _classCallCheck(this, ButtonRadio);

    this.element = $(element);
    this.options = $.extend(true, {}, defaultOptions, options);
    var colors = [];

    // Turn radios into btn-group
    var $radios = this.element.find(this.options.selector);

    options = this.options;

    $radios.addClass(options.buttonClass).addClass(options.color['default']);

    $radios.on('click', function (e) {
      var $btn = $(e.currentTarget);
      var $group = $btn.parent().find('.' + options.buttonClass);
      var $input = $btn.find('input[type=radio]');

      if ($input.prop('disabled') || $input.prop('readonly')) {
        return;
      }

      if (!$input.prop('checked')) {
        $group.addClass(options.color.default).removeClass(options.activeClass).removeClass(colors);

        $btn.addClass(options.activeClass).addClass($input.attr('data-color-class')).removeClass(options.color.default);

        $input.prop('checked', true);
        $input.trigger('change');
      }
    });

    $radios.each(function () {
      var $radio = $(this);
      var $input = $radio.find('input');
      var $label = $radio.find('label');
      var $text = $('<span>' + $label.text() + '</span>');

      $label.hide();
      $input.hide();
      $radio.prepend($text);
      $radio.removeClass('radio');

      // Prepare color schema
      var color = $input.attr('data-color-class');

      if (color == null) {
        switch ($input.val()) {
          case '':
            color = options.color.blue;
            break;

          case '0':
            color = options.color.red;
            break;

          default:
            color = options.color.green;
            break;
        }

        $input.attr('data-color-class', color);
      }

      colors.push(color);

      if ($input.prop('checked')) {
        $radio.removeClass(options.color.default).addClass(options.activeClass).addClass(color);
      }

      if ($input.prop('disabled')) {
        $radio.addClass('disabled');
      }

      if ($input.prop('readonly')) {
        $radio.addClass('readonly');
      }
    });

    $radios.parent().trigger('button-radio.loaded');

    // Make color elements unique
    colors = $.unique(colors);
  };

  /**
   * Push to plugins.
   *
   * @param   {Object} options
   *
   * @returns {ButtonRadio}
   */


  $.fn[plugin] = function (options) {
    if (!this.data('phoenix.' + plugin)) {
      this.data('phoenix.' + plugin, new ButtonRadio(this, options));
    }

    return this.data('phoenix.' + plugin);
  };
})(jQuery);
//# sourceMappingURL=button-radio.js.map
