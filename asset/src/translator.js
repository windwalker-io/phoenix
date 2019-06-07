/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * Phoenix.Translator
 */
(() => {
  "use strict";

  class PhoenixTranslator extends PhoenixPlugin {
    static get is() { return 'Translator' }

    static get proxies() {
      return {
        trans: 'translate',
        __: 'translate',
        addLanguage: 'addKey',
      };
    }

    constructor() {
      super();

      this.keys = {};
    }

    /**
     * Translate a string.
     *
     * @param {string} text
     * @param {Array}  args
     * @returns {string}
     */
    translate(text, ...args) {
      const key = this.normalize(text);

      if (args.length) {
        return this.sprintf(text, ...args);
      }

      const translated = this.find(key);

      return translated !== null ? translated : this.wrapDebug(text, false);
    }

    /**
     * Sptintf language string.
     * @param {string} text
     * @param {Array} args
     */
    sprintf(text, ...args) {
      return this.phoenix.vsprintf(this.find(text), args);
    }

    /**
     * Find text.
     * @param {string} key
     * @returns {*}
     */
    find(key) {
      const langs = this.phoenix.data('phoenix.languages');

      if (langs[key]) {
        return langs[key];
      }

      return null;
    }

    /**
     * Has language key.
     * @param {string} key
     * @returns {boolean}
     */
    has(key) {
      const langs = this.phoenix.data('phoenix.languages');

      return langs[key] !== undefined;
    }

    /**
     * Add language key.
     *
     * @param {string} key
     * @param {string} value
     *
     * @return {PhoenixTranslator}
     */
    addKey(key, value) {
      const data = {};
      data[this.normalize(key)] = value;

      this.phoenix.data('phoenix.languages', data);

      return this;
    }

    /**
     * Replace all symbols to dot(.).
     *
     * @param {string} text
     *
     * @return {string}
     */
     normalize(text) {
       return text.replace(/[^A-Z0-9]+/ig, '.');
     }

     wrapDebug(text, success) {
       if (this.phoenix.isDebug()) {
         if (success) {
           return '**' + text + '**';
         }

         return '??' + text + '??';
       }

       return text;
     }
  }

  window.PhoenixTranslator = PhoenixTranslator;
})();
