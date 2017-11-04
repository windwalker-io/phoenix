/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

var Phoenix;

/**
 * Phoenix.Translator
 */
(function (Phoenix) {
    "use strict";

    Phoenix.Translator = {
        keys: {},

        /**
         * Translate a string.
         *
         * @param {string} text
         *
         * @returns {string}
         */
        translate: function (text) {
            var key = this.normalize(text);

            if (this.keys[key]) {
                return this.keys[key];
            }

            return text;
        },

        sprintf: function (text) {
            var args = Array.prototype.slice.call(arguments);

            args[0] = this.translate(text);

            return sprintf.apply(sprintf, args);
        },

        /**
         * Add language key.
         *
         * @param {string} key
         * @param {string} value
         *
         * @return {Phoenix.Translator}
         */
        addKey: function (key, value) {
            this.keys[this.normalize(key)] = value;

            return this;
        },

        /**
         * Replace all symbols to dot(.).
         *
         * @param {string} text
         *
         * @return {string}
         */
        normalize: function (text) {
            return text.replace(/[^A-Z0-9]+/ig, '.');
        }
    };
})(Phoenix || (Phoenix = {}));
