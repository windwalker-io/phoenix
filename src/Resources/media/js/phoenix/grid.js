/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

;
(function($)
{
    "use strict";

    /**
     * Plugin Name.
     *
     * @type {string}
     */
    var pluginName = "grid";

    /**
     * Default options.
     *
     * @type {Object}
     */
    var defaultOptions = {
        selector: {
            form: '#admin-form',
            search: {
                container: '.search-container',
                button: '.search-button',
                clearButton: '.search-clear-button'
            },
            filter: {
                container: '.filter-container',
                button: '.filter-toggle-button'
            },
            sort: {
                button: 'a[data-sort-button]'
            }
        }
    };

    /**
     * PhoenixGrid constructor.
     *
     * @param element
     * @param options
     *
     * @constructor
     */
    function PhoenixGrid(element, options)
    {
        this.element = element;
        this.options = $.extend(true, {}, defaultOptions, options);

        var selector = this.options.selector;

        this.element = $(selector.element);
        this.searchContainer = this.element.find(selector.search.container);
        this.searchButton = this.element.find(selector.search.button);
        this.searchClearButton = this.element.find(selector.search.clearButton);
        this.filterContainer = this.element.find(selector.filter.container);
        this.filterButton = this.element.find(selector.filter.button);
        this.sortButtons = this.element.find(selector.sort.button);

        this.registerEvents();
    }

    PhoenixGrid.prototype = {

        registerEvents: function()
        {
            var self = this;

            this.searchClearButton.click(function(event)
            {
                self.searchContainer.find('input, textarea, select').val('');
                self.filterContainer.find('input, textarea, select').val('');

                self.element.submit();
            });

            this.filterButton.click(function(event)
            {
                self.toggleFilter();
                event.stopPropagation();
                event.preventDefault();
            });

            this.sortButtons.click(function(event)
            {
                self.sort(this, event);
            });
        },

        toggleFilter: function()
        {
            if (this.filterContainer.hasClass('shown'))
            {
                this.filterButton.removeClass('btn-default').addClass('btn-primary');
                this.filterContainer.hide('fast');
                this.filterContainer.removeClass('shown');
            }
            else
            {
                this.filterButton.removeClass('btn-primary').addClass('btn-default');
                this.filterContainer.show('fast');
                this.filterContainer.addClass('shown');
            }
        },

        sort: function(element, event)
        {
            var $element = $(element);
            var ordering = $element.attr('data-sort-field');
            var direction = $element.attr('data-sort-direction');

            var orderingInput = $element.find('input[name=list_ordering]');

            if (!orderingInput.length)
            {
                orderingInput = $('<input name="list_ordering" type="hidden" value="" />');

                this.element.append(orderingInput);
            }

            var directionInput = $element.find('input[name=list_direction]');

            if (!directionInput.length)
            {
                directionInput = $('<input name="list_direction" type="hidden" value="" />');

                this.element.append(directionInput);
            }

            orderingInput.val(ordering);
            directionInput.val(direction);

            this.element.submit();
        }
    };

    $.fn[pluginName] = function(options)
    {
        return this.each(function()
        {
            if (!$.data(this, "plugin_" + pluginName))
            {
                $.data(this, "plugin_" + pluginName, new PhoenixGrid(this, options));
            }
        });
    };

})(jQuery);
