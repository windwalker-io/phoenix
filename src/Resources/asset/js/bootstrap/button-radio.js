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
  var ButtonRadio = function (element, options) {
    this.element = $(element);
    this.options = $.extend(true, {}, defaultOptions, options);

    // Turn radios into btn-group
    var $radios = this.element.find(this.options.selector);

    options = this.options;

    $radios.addClass(options.buttonClass)
      .addClass(options.color['default']);

    $radios.on('click', function () {
      var btn = $(this);
      var group = btn.parent().find('.' + options.buttonClass);
      var input = btn.find('input[type=radio]');

      if (input.prop('disabled') || input.prop('readonly')) {
        return;
      }

      if (!input.prop('checked')) {
        group
          .addClass(options.color.default)
          .removeClass(options.activeClass)
          .removeClass(options.color.green)
          .removeClass(options.color.red)
          .removeClass(options.color.blue);

        if (input.val() === '') {
          btn.addClass(options.activeClass).addClass(options.color.blue).removeClass(options.color.default);
        } else if (input.val() === '0') {
          btn.addClass(options.activeClass).addClass(options.color.red).removeClass(options.color.default);
        } else {
          btn.addClass(options.activeClass).addClass(options.color.green).removeClass(options.color.default);
        }

        input.prop('checked', true);
        input.trigger('change');
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

      if ($input.prop('checked')) {
        if ($input.val() === '') {
          $radio.addClass(options.activeClass).addClass(options.color.blue);
        }
        else if ($input.val() === '0') {
          $radio.addClass(options.activeClass).addClass(options.color.red);
        }
        else {
          $radio.addClass(options.activeClass).addClass(options.color.green);
        }
      }

      if ($input.prop('disabled')) {
        $radio.addClass('disabled');
      }

      if ($input.prop('readonly')) {
        $radio.addClass('readonly');
      }
    });

    $radios.parent().trigger('button-radio.loaded');

    // TODO: Must rewrite to fix for strict mode: Octal literals are not allowed in strict mode.
    // add color classes to chosen field based on value
    // $('select[class^="chzn-color"], select[class*=" chzn-color"]').on('liszt:ready', function()
    // {
    //     var select = $(this);
    //     var cls = this.className.replace(/^.(chzn-color[a-z0-9-_]*)$.*/, '\1');
    //     var container = select.next('.chzn-container').find('.chzn-single');
    //     container.addClass(cls).attr('rel', 'value_' + select.val());
    //     select.on('change click', function()
    //     {
    //         container.attr('rel', 'value_' + select.val());
    //     });
    // });
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
