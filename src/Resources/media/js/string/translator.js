/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

;(function()
{
    "use strict";

    window.PhoenixTranslator = {
        keys: {},

        /**
         * Translate a string.
         *
         * @param {string} text
         * @returns {string}
         */
        translate: function(text)
        {
            if (this.keys[text])
            {
                return this.keys[text];
            }

            return text;
        },

        /**
         * Add language key.
         *
         * @param {string} key
         * @param {string} value
         */
        addKey: function(key, value)
        {
            this.keys[key] = value;
        }
    };
})();
