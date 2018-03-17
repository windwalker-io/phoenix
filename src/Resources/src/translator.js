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
      var key = this.normalize(text);

      if (args.length) {
        return this.sprintf(text, ...args);
      }

      const langs = this.phoenix.data('phoenix.languages');

      if (langs[key]) {
        return langs[key];
      }

      return text;
    }

    sprintf(text, ...args) {
      args[0] = this.translate(text);

      return sprintf.apply(sprintf, args);
    }

    /**
     * Add language key.
     *
     * @param {string} key
     * @param {string} value
     *
     * @return {Phoenix.Translator}
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
  }

  window.PhoenixTranslator = PhoenixTranslator;
})();
