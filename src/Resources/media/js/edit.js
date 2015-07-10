/**
 * Part of asukademy project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

/**
 * Part of asukademy project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
	"use strict";

	window.RikiEdit = window.RikiEdit || {

		changed: function(state)
		{
			this.change = state;
		},

		isChanged: function()
		{
			return this.change;
		},

		/**
		 * @link http://stackoverflow.com/a/1119324
		 */
		confirmOnPageExit: function (form)
		{
			var self = this;

			form = form || $('#adminForm');

			form.find('input, select, textarea').on('change', function()
			{
				self.changed(true);
			});

			$(window).on('beforeunload', function(e)
			{
				if (!self.isChanged())
				{
					return;
				}

				// If we haven't been passed the event get the window.event
				e = e || window.event;

				var message = '尚未儲存更改過的內容';

				// For IE6-8 and Firefox prior to version 4
				if (e)
				{
					e.returnValue = message;
				}

				// For Chrome, Safari, IE8+ and Opera 12+
				return message;
			});
		}
	}
})(jQuery);
