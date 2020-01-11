"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

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
(function () {
  'use strict';

  var LoadTab =
  /*#__PURE__*/
  function () {
    /**
     * Class init.
     *
     * @param {jQuery} $element
     * @param {Number} time
     *
     * @constructor
     */
    function LoadTab($element, time) {
      var _this = this;

      _classCallCheck(this, LoadTab);

      time = time || 30;
      this.$element = $element;
      this.tabButtons = $element.find('a[data-toggle="tab"]');
      this.storageKey = 'tab-href-' + this.hashCode(location.href);
      this.bindEvents();
      setTimeout(function () {
        _this.switchTab();
      }, time);
    }
    /**
     * Bind events.
     */


    _createClass(LoadTab, [{
      key: "bindEvents",
      value: function bindEvents() {
        var _this2 = this;

        this.tabButtons.on('click', function (e) {
          // Store the selected tab href in localstorage
          window.localStorage.setItem(_this2.storageKey, $(e.currentTarget).attr('href'));
        });
      }
      /**
       * Active tab.
       *
       * @param {string} href
       */

    }, {
      key: "activateTab",
      value: function activateTab(href) {
        var $el = this.$element.find("a[data-toggle=\"tab\"][href*=\"".concat(href, "\"]"));
        $el.tab('show');
      }
      /**
       * Has tab.
       *
       * @param {string} href
       *
       * @returns {*}
       */

    }, {
      key: "hasTab",
      value: function hasTab(href) {
        return this.$element.find("a[data-toggle=\"tab\"][href*=\"".concat(href, "\"]")).length;
      }
      /**
       * Switch tab.
       *
       * @returns {boolean}
       */

    }, {
      key: "switchTab",
      value: function switchTab() {
        if (localStorage.getItem(this.storageKey)) {
          // When moving from tab area to a different view
          if (!this.hasTab(localStorage.getItem(this.storageKey))) {
            localStorage.removeItem(this.storageKey);
            return true;
          } // Clean default tabs


          this.$element.find('a[data-toggle="tab"]').parent().removeClass('active');
          var tabhref = localStorage.getItem(this.storageKey); // Add active attribute for selected tab indicated by url

          this.activateTab(tabhref); // Check whether internal tab is selected (in format <tabname>-<id>)

          var seperatorIndex = tabhref.indexOf('-');

          if (seperatorIndex !== -1) {
            var singular = tabhref.substring(0, seperatorIndex);
            var plural = singular + 's';
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

    }, {
      key: "hashCode",
      value: function hashCode(text) {
        var hash = 0;
        var word;

        if (text.length === 0) {
          return hash;
        }

        for (var i = 0; i < text.length; i++) {
          word = text.charCodeAt(i);
          hash = (hash << 5) - hash + word;
          hash = hash & hash; // Convert to 32bit integer
        }

        return hash;
      }
    }]);

    return LoadTab;
  }();

  window.LoadTab = LoadTab;
})();
//# sourceMappingURL=tab-state.js.map
