/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
    var defaultOptions = {

    };

    /**
     * Class init.
     *
     * @param {jQuery} element
     * @param {Object} options
     * @constructor
     */
    var PhoenixValidation = function(element, options)
    {
        options = $.extend({}, defaultOptions, options);

        this.form = element || $;
        this.options = options;
        this.validators = [];
        this.inputs = this.form.find('input, select, textarea');

        this.init();
    };

    PhoenixValidation.prototype = {
        isVaild: function()
        {

        },

        validField: function(input)
        {
            var $input = $(input);

            if ($input.attr('disabled'))
            {
                this.showResponse(true, $input)
                return true;
            }

            if ($input.attr('required') || $input.hasClass('required'))
            {
                // Handle radio & checkboxes

            }
        },

        showResponse: function(state, $input)
        {

        },

        /**
         * Add validator handler.
         *
         * @param name
         * @param validator
         * @param options
         * @returns {PhoenixValidation}
         */
        addValidator: function(name, validator, options)
        {
            this.validators[name] = {
                handler: validator,
                options: options
            };

            return this;
        },

        init: function()
        {
            // Default handlers
            this.addValidator('username', function(value, element)
            {
                var regex = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&]", "i");
                return !regex.test(value);
            });

            this.addValidator('password', function(value, element)
            {
                var regex = /^\S[\S ]{2,98}\S$/;
                return regex.test(value);
            });

            this.addValidator('numeric', function(value, element)
            {
                var regex = /^(\d|-)?(\d|,)*\.?\d*$/;
                return regex.test(value);
            });

            this.addValidator('email', function(value, element)
            {
                value = punycode.toASCII(value);
                var regex = /^[a-zA-Z0-9.!#$%&â€™*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                return regex.test(value);
            });
        }
    };

    $.fn.validation = function (options)
    {
        if (!this.data('phoenix.validation'))
        {
            this.data('phoenix.validation', new PhoenixValidation(this, options));
        }

        return this.data('phoenix.validation');
    };

})(jQuery);
