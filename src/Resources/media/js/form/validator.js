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
     * @param {string} selector
     * @param {Object} options
     * @constructor
     */
    var PhoenixValidator = function(selector, options)
    {
        options = $.extend({}, defaultOptions, options);

        this.form = $(selector);
        this.options = options;
        this.validators = [];

        this.init();
    };

    PhoenixValidator = {
        isVaild: function()
        {

        },

        validate: function()
        {

        },

        /**
         * Add validator handler.
         *
         * @param name
         * @param validator
         * @param options
         * @returns {PhoenixValidator}
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

    window.PhoenixValidator = PhoenixValidator;

})(jQuery);
