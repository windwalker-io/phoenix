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
		 * @param  {string} url
		 * @param  {Object} queries
         * @param  {string} method
		 * @param  {string} customMethod
		 *
		 * @returns {boolean}
		 */
		submit: function(url, queries, method, customMethod)
		{
			var form = this.form;

			var methodInput = form.find('input[name="_method"]');

			if(!methodInput.length)
			{
				methodInput = $('<input name="_method" type="hidden">');

				form.append(methodInput);
			}

			methodInput.val(customMethod);

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
		 * @param  {string} url
		 * @param  {Object} queries
		 * @param  {string} customMethod
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
		 * @param  {string} url
		 * @param  {Object} queries
		 * @param  {string} customMethod
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
		 * @param  {string} url
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
		 * @param  {string} url
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
		 * @param  {string} url
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
		 * @param  {string} url
		 * @param  {Object} queries
		 * @param  {string} msg
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
		},

		/**
		 * Update a row.
		 *
		 * @param  {number} row
		 * @param  {string} url
		 * @param  {Object} queries
		 */
		updateRow: function(row, url, queries)
		{
			this.toggleAll(false);

			var ch = this.form.find('input.grid-checkbox[data-row-number=' + row + ']');

			if (!ch.length)
			{
				throw new Error('Checkbox of row: ' + row + ' not found.');
			}

			ch[0].checked = true;

			this.patch(url, queries);
		},

		/**
		 * Toggle all checkboxes.
		 *
		 * @param  {boolean}  value  Checked or unchecked.
		 */
		toggleAll: function(value)
		{
			var checkboxes = this.form.find('input.grid-checkbox[type=checkbox]');

			$.each(checkboxes, function(i, e)
			{
				// A little pretty effect
				setTimeout(function()
				{
					e.checked = value;
				}, (150 / checkboxes.length) * i);
			});
		},

		/**
		 * Count checked checkboxes.
		 *
		 * @returns {int}
		 */
		countChecked: function()
		{
			return this.getChecked().length;
		},

		/**
		 * Get Checked boxes.
		 *
		 * @returns {Element[]}
		 */
		getChecked: function()
		{
			var checkboxes = this.form.find('input.grid-checkbox[type=checkbox]'),
				result = [];

			$.each(checkboxes, function(i, e)
			{
				if (e.checked)
				{
					result.push(e);
				}
			});

			return result;
		},

		/**
		 * Validate there has one or more checked boxes.
		 *
		 * @param   {string}  msg
		 * @param   {Event}   event
		 *
		 * @returns {PhoenixCore}
		 */
		validateChecked: function(msg, event)
		{
			msg = msg || 'Please check one or more items.';

			if (!this.countChecked())
			{
				alert(msg);

				event.stopPropagation();
				event.preventDefault();

				throw new Error(msg);
			}

			return this;
		},

		/**
		 * Reorder all.
		 *
		 * @param   {string}  url
		 * @param   {Object}  queries
		 *
		 * @returns {boolean}
		 */
		reorderAll: function(url, queries)
		{
			queries = queries || {};
			queries['task'] = queries['task'] || 'reorder';

			return this.patch(url, queries);
		},

		/**
		 * Reorder items.
		 *
		 * @param  {int}     row
		 * @param  {int}     offset
		 * @param  {string}  url
		 * @param  {Object}  queries
		 *
		 * @returns {boolean}
		 */
		reorder: function(row, offset, url, queries)
		{
			var input = this.form.find('input[data-order-row=' + row + ']');
			var tr    = input.parents('tr');
			var group = tr.attr('data-order-group');
			var input2;

			input.val(parseInt(input.val()) + parseFloat(offset));

			if (offset > 0)
			{
				if (group)
				{
					input2 = tr.nextAll('tr[data-order-group=' + group + ']').first().find('input[data-order-row]');
				}
				else
				{
					input2 = tr.next().find('input[data-order-row]');
				}
			}
			else if (offset < 0)
			{
				if (group)
				{
					input2 = tr.prevAll('tr[data-order-group=' + group + ']').first().find('input[data-order-row]');
				}
				else
				{
					input2 = tr.prev().find('input[data-order-row]');
				}
			}

			input2.val(parseInt(input2.val()) - parseFloat(offset));

			return this.reorderAll(url, queries);
		}
	};

	window.PhoenixCore = PhoenixCore;
})(jQuery);
