/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

;(function ($, undefined)
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
		searchContainerSelector: '.search-container',
		filterContainerSelector: '.filter-container',
		filterButtonSelector:    '.filter-toggle-button'
	};

	/**
	 * Gird constructor.
	 *
	 * @param element
	 * @param options
	 *
	 * @constructor
	 */
	function Grid(element, options)
	{
		this.element = element;
		this.options = $.extend({}, defaultOptions, options);

		this.searchContainer = $(this.options.searchContainerSelector);
		this.filterContainer = $(this.options.filterContainerSelector);
		this.filterButton    = $(this.options.filterButtonSelector);

		this.registerEvents();
	}

	Grid.prototype = {

		registerEvents: function () {
			var self = this;

			this.filterButton.click(function (event)
			{
				self.toggleFilter();
				event.stopPropagation();
				event.preventDefault();
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
		}
	};

	$.fn[pluginName] = function (options)
	{
		return this.each(function ()
		{
			if (!$.data(this, "plugin_" + pluginName))
			{
				$.data(this, "plugin_" + pluginName, new Grid(this, options));
			}
		});
	};

})(jQuery);
