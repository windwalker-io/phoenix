/**
 * Part of asukademy project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
	"use strict";

	var defaultOptions = {

	};

	/**
	 * Class init.
	 *
	 * @param {Element} form
	 * @param {Object}  options
	 *
	 * @constructor
	 */
	var PhoenixCore = function(form, options)
	{
		if (typeof form == 'string')
		{
			form = $(form);
		}

		options = $.extend({}, defaultOptions, options);

		this.form = form;
		this.options = options;
	};

	PhoenixCore.prototype = {

		/**
		 * Make a request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
         * @param  {String} method
		 * @param  {String} customMethod
		 *
		 * @returns {boolean}
		 */
		submit: function(url, queries, method, customMethod)
		{
			var form = this.form;

			if (customMethod)
			{
				var methodInput = form.find('input[name="_method"]');

				if(!methodInput.length)
				{
					methodInput = $('<input name="_method" type="hidden">');

					form.append(methodInput);
				}

				methodInput.val(customMethod);
			}

			// Set queries into form.
			if (queries)
			{
				var input;

				$.each(queries, function(key, value)
				{
					input = form.find('input[name="' + key + '"]');

					if (!input.length)
					{
						input = $('<input name="' + key + '" type="hidden">');

						form.append(input);
					}

					input.val(value);
				});
			}

			if (url)
			{
				form.attr('action', url);
			}

			if (method)
			{
				form.attr('method', method);
			}

			form.submit();

			return true;
		},

		/**
		 * Make a GET request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 * @param  {String} customMethod
		 *
		 * @returns {boolean}
		 */
		get: function(url, queries, customMethod)
		{
			return this.submit(url, queries, 'GET', customMethod);
		},

		/**
		 * Post form.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 * @param  {String} customMethod
		 *
		 * @returns {boolean}
		 */
		post: function (url, queries, customMethod)
		{
			return this.submit(url, queries, 'POST', customMethod);
		},

		/**
		 * Make a PUT request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 *
		 * @returns {boolean}
		 */
		put: function(url, queries)
		{
			return this.post(url, queries, 'PUT');
		},

		/**
		 * Make a PATCH request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 *
		 * @returns {boolean}
		 */
		patch: function(url, queries)
		{
			return this.post(url, queries, 'PATCH');
		},

		/**
		 * Make a DELETE request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 *
		 * @returns {boolean}
		 */
		delete: function(url, queries)
		{
			return this.post(url, queries, 'DELETE');
		},

		/**
		 * Make a DELETE request.
		 *
		 * @param  {String} url
		 * @param  {Object} queries
		 * @param  {String} msg
		 *
		 * @returns {boolean}
		 */
		deleteItem: function(url, queries, msg)
		{
			msg = msg || 'Are you sure';

			if (!confirm(msg))
			{
				return false;
			}

			return this.delete(url, queries);
		}
	};

	window.PhoenixCore = PhoenixCore;
})(jQuery);
