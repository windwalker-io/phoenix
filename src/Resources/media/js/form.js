/**
 * Part of asukademy project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

;(function($)
{
	"use strict";

	window.RikiForm = window.RikiForm || {

		post: function (url, method)
		{
			method = method || 'post';

			var form = $('#adminForm'),
				methodInput = $('#adminForm input[name="_method"]');

			if(!methodInput.length)
			{
				methodInput = $('<input name="_method" type="hidden">');

				form.append(methodInput);
			}

			methodInput.val(method);
			form.attr('action', url).attr('method', 'post');

			// Cancel change confirm
			if (window.RikiEdit)
			{
				window.RikiEdit.changed(false);
			}

			form.submit();

			return true;
		},

		deleteItem: function(url, msg)
		{
			msg = msg || '你確定嗎？ 這個動作無法還原。';

			if (!confirm(msg))
			{
				return false;
			}

			return this.post(url, 'delete');
		}
	}
})(jQuery);
