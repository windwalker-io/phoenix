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
        this.inputs = this.form.find('input, select, textarea, div.radio-container, div.checkbox-container');

        this.registerDefaultValidators();
        this.registerEvents();
    };

    PhoenixValidation.prototype = {
        isVaild: function()
        {

        },

        validate: function()
        {
            var self = this;

            this.inputs.each(function()
            {
                self.validField(this);
            });

            return false;
        },

        validField: function(input)
        {
            var $input = $(input), tagName;

            if ($input.attr('disabled'))
            {
                this.showResponse(true, $input)
                return true;
            }

            if ($input.attr('required') || $input.hasClass('required'))
            {
                // Handle radio & checkboxes
                tagName = $input.prop("tagName").toLowerCase();

                if (tagName === 'div' && ($input.hasClass('radio-container') || $input.hasClass('checkbox-container')))
                {
                    if (!$input.find('input:checked').length)
                    {
                        this.showResponse(false, $input);

                        return false;
                    }
                }
            }

            if ($input.attr('type') == 'radio' || $input.attr('type') == 'checkbox')
            {
                return true;
            }

            this.showResponse(true, $input);
        },

        showResponse: function(state, $input)
        {
            var control = $input.parents('.form-group').first();

            if (!state)
            {
                control.addClass('has-error has-feedback');

                if (!control.find('.form-control-feedback').length)
                {
                    var feedback = $('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                    control.prepend(feedback);
                }

                if (!control.find('.help-block').length)
                {
                    var help = $('<small class="help-block">Some thing</small>');
                    $input.append(help);
                }
            }
            else
            {
                control.find('.form-control-feedback').remove();
                control.find('.help-block').remove();
                control.removeClass('has-error').removeClass('has-feedback');
            }
        },

        findLabel: function($input)
        {
            var id, label;

            if (id = $input.attr('id'))
            {
                label = $('label[for=' + id + ']');

                if (label.length)
                {
                    return label.first();
                }
            }
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

        registerEvents: function()
        {
            var self = this;

            this.form.on('submit', function(event)
            {
                if (!self.validate())
                {
                    event.stopPropagation();
                    event.preventDefault();

                    return false;
                }

                return true;
            });
        },

        registerDefaultValidators: function()
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
