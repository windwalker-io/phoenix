/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
    "use strict";

    /**
     * Plugin name.
     *
     * @type {string}
     */
    var plugin = 'validation';

    var defaultOptions = {
        events: ['change']
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

        this.SUCCESS = 'success';
        this.NONE    = 'none';
        this.EMPTY   = 'empty';
        this.WARNING = 'warning';

        this.element = element || $;
        this.options = options;
        this.validators = [];
        this.inputs = this.element.find('input, select, textarea, div.input-list-container');

        this.registerDefaultValidators();
        this.registerEvents();
    };

    PhoenixValidation.prototype = {
        /**
         * Validate All.
         *
         * @returns {boolean}
         */
        validateAll: function()
        {
            var self = this, inValid = [];

            this.inputs.each(function()
            {
                if (!self.validate(this))
                {
                    inValid.push(this);
                }
            });

            return inValid.length <= 0;
        },

        /**
         * Validate.
         *
         * @param {jQuery} input
         * @returns {boolean}
         */
        validate: function(input)
        {
            var $input = $(input), tagName, className, validator;

            if ($input.attr('disabled'))
            {
                this.showResponse(this.NONE, $input);

                return true;
            }

            if ($input.attr('type') == 'radio' || $input.attr('type') == 'checkbox')
            {
                return true;
            }

            if ($input.attr('required') || $input.hasClass('required'))
            {
                // Handle radio & checkboxes
                if ($input.prop("tagName").toLowerCase() === 'div' && $input.hasClass('input-list-container'))
                {
                    if (!$input.find('input:checked').length)
                    {
                        this.showResponse(this.EMPTY, $input);

                        return false;
                    }
                }

                // Handle all fields and checkbox
                else if (!$input.val() || ($input.attr('type') === 'checkbox' && !$input.is(':checked')))
                {
                    this.showResponse(this.EMPTY, $input);

                    return false;
                }
            }

            // Is value exists, validate this type.
            className = $input.attr('class');

            if (className)
            {
                validator = className.match(/validate-([a-zA-Z0-9\_|-]+)/);
            }

            // Empty value and no validator config, set response to none.
            if (!$input.val() || !validator)
            {
                this.showResponse(this.NONE, $input);

                return true;
            }

            validator = this.validators[validator[1]];

            if (!validator || !validator.handler)
            {
                this.showResponse(this.NONE, $input);

                return true;
            }

            if (!validator.handler($input.val(), $input))
            {
                this.showResponse(this.WARNING, $input, validator.options.notice);

                return false;
            }

            this.showResponse(this.SUCCESS, $input);

            return true;
        },

        /**
         * Show response on input.
         *
         * @param {string} state
         * @param {jQuery} $input
         * @param {string} help
         *
         * @returns {boolean}
         */
        showResponse: function(state, $input, help)
        {
            var $control = $input.parents('.form-group').first();

            this.removeResponce($control);

            if (state != this.NONE)
            {
                var icon, color;

                switch (state)
                {
                    case this.SUCCESS:
                        color = 'success';
                        icon  = 'ok';
                        break;

                    case this.EMPTY:
                        color = 'error';
                        icon  = 'remove';
                        break;

                    case this.WARNING:
                        color = 'warning';
                        icon  = 'warning-sign';
                        break;
                }

                $control.addClass('has-' + color + ' has-feedback');

                var feedback = $('<span class="glyphicon glyphicon-' + icon + ' form-control-feedback" aria-hidden="true"></span>');
                $control.prepend(feedback);

                if (help)
                {
                    var helpElement = $('<small class="help-block">' + help + '</small>');

                    var tagName = $input.prop('tagName').toLowerCase();

                    if (tagName == 'div')
                    {
                        $input.append(helpElement);
                    }
                    else
                    {
                        $input.parent().append(helpElement);
                    }
                }
            }

            $input.trigger({
                type: 'phoenix.validate.' + state,
                input: $input,
                state: state,
                help: help
            });

            return state;
        },

        removeResponce: function($element)
        {
            $element.find('.form-control-feedback').remove();
            $element.removeClass('has-error')
                .removeClass('has-success')
                .removeClass('has-warning')
                .removeClass('has-feedback');
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
            options = options || {};

            this.validators[name] = {
                handler: validator,
                options: options
            };

            return this;
        },

        /**
         * Register events.
         */
        registerEvents: function()
        {
            var self = this;

            this.element.on('submit', function(event)
            {
                if (!self.validateAll())
                {
                    event.stopPropagation();
                    event.preventDefault();

                    return false;
                }

                return true;
            });

            $.each(this.options.events, function()
            {
                self.inputs.on(this, function()
                {
                    self.validate(this);
                });
            });
        },

        /**
         * Register default validators.
         */
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

            this.addValidator('url', function(value, element)
            {
                value = punycode.toASCII(value);
                var regex = /^(?:(?:https?|ftp):\/\/)(?:\S+(?::\S*)?@)?(?:(?!10(?:\.\d{1,3}){3})(?!127(?:\.\d{1,3}){3})(?!169\.254(?:\.\d{1,3}){2})(?!192\.168(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]+-?)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/[^\s]*)?$/i;
                return regex.test(value);
            });
        }
    };

    $.fn[plugin] = function (options)
    {
        if (!this.data('phoenix.' + plugin))
        {
            this.data('phoenix.' + plugin, new PhoenixValidation(this, options));
        }

        return this.data('phoenix.' + plugin);
    };

})(jQuery);
