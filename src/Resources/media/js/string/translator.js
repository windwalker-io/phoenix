/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

;(function($)
{
    window.PhoenixTranslator = {
        keys: {},

        translate: function(text)
        {
            if (this.keys[text])
            {
                return this.keys[text];
            }

            return text;
        },

        sprintf: function(text)
        {

        },

        plural: function(text)
        {

        },

        addKey: function(key, value)
        {
            this.keys[key] = value;
        }
    };

})(jQuery);
