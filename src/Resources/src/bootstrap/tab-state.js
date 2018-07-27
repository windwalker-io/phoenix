/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

/**
 * JavaScript behavior to allow selected tab to be remained after save or page reload
 * keeping state in localstorage
 *
 * @see  https://github.com/joomla/joomla-cms/blob/staging/media/system/js/tabs-state.js
 */
(() => {
  'use strict';

  class LoadTab {
    /**
     * Class init.
     *
     * @param {jQuery} $element
     * @param {Number} time
     *
     * @constructor
     */
    constructor($element, time) {
      time = time || 100;

      this.$element = $element;
      this.tabButtons = $element.find('a[data-toggle="tab"]');
      this.storageKey = 'tab-href-' + this.hashCode(location.href);

      this.bindEvents();

      setTimeout(() => {
        this.switchTab();
      }, time);
    }

    /**
     * Bind events.
     */
    bindEvents() {
      this.tabButtons.on('click', e => {
        // Store the selected tab href in localstorage
        window.localStorage.setItem(this.storageKey, $(e.currentTarget).attr('href'));
      });
    }

    /**
     * Active tab.
     *
     * @param {string} href
     */
    activateTab(href) {
      const $el = this.$element.find(`a[data-toggle="tab"][href*="${href}"]`);
      $el.tab('show');
    }

    /**
     * Has tab.
     *
     * @param {string} href
     *
     * @returns {*}
     */
    hasTab(href) {
      return this.$element.find(`a[data-toggle="tab"][href*="${href}"]`).length;
    }

    /**
     * Switch tab.
     *
     * @returns {boolean}
     */
    switchTab() {
      if (localStorage.getItem('tab-href')) {
        // When moving from tab area to a different view
        if (!this.hasTab(localStorage.getItem(this.storageKey))) {
          localStorage.removeItem(this.storageKey);
          return true;
        }

        // Clean default tabs
        this.$element.find('a[data-toggle="tab"]').parent().removeClass('active');

        const tabhref = localStorage.getItem(this.storageKey);

        // Add active attribute for selected tab indicated by url
        this.activateTab(tabhref);

        // Check whether internal tab is selected (in format <tabname>-<id>)
        const seperatorIndex = tabhref.indexOf('-');

        if (seperatorIndex !== -1) {
          const singular = tabhref.substring(0, seperatorIndex);
          const plural = singular + 's';

          this.activateTab(plural);
        }
      }
    }

    /**
     * Hash code.
     *
     * @param {String} text
     *
     * @returns {number}
     */
    hashCode(text) {
      let hash = 0;
      let word;

      if (text.length === 0) {
        return hash;
      }

      for (let i = 0; i < text.length; i++) {
        word = text.charCodeAt(i);
        hash = ((hash << 5) - hash) + word;
        hash = hash & hash; // Convert to 32bit integer
      }

      return hash;
    }
  }

  window.LoadTab = LoadTab;
})();
