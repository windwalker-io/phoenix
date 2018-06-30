'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * PhoenixGrid
 */
;(function ($) {
  "use strict";

  var PhoenixGrid = function (_PhoenixJQueryPlugin) {
    _inherits(PhoenixGrid, _PhoenixJQueryPlugin);

    function PhoenixGrid() {
      _classCallCheck(this, PhoenixGrid);

      return _possibleConstructorReturn(this, (PhoenixGrid.__proto__ || Object.getPrototypeOf(PhoenixGrid)).apply(this, arguments));
    }

    _createClass(PhoenixGrid, null, [{
      key: 'is',
      get: function get() {
        return 'Grid';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          grid: 'createPlugin'
        };
      }

      /**
       * Plugin name.
       * @returns {string}
       */

    }, {
      key: 'pluginName',
      get: function get() {
        return 'grid';
      }
    }, {
      key: 'pluginClass',
      get: function get() {
        return PhoenixGridElement;
      }
    }, {
      key: 'defaultOptions',


      /**
       * Default options.
       * @returns {Object}
       */
      get: function get() {
        return {};
      }
    }]);

    return PhoenixGrid;
  }(PhoenixJQueryPlugin);

  var PhoenixGridElement = function () {
    _createClass(PhoenixGridElement, null, [{
      key: 'defaultOptions',
      get: function get() {
        return {
          mainSelector: '',
          selector: {
            search: {
              container: '.search-container',
              button: '.search-button',
              clearButton: '.search-clear-button'
            },
            filter: {
              container: '.filter-container',
              button: '.filter-toggle-button'
            },
            sort: {
              button: 'a[data-sort-button]'
            }
          }
        };
      }
    }]);

    function PhoenixGridElement(element, options, phoenix) {
      _classCallCheck(this, PhoenixGridElement);

      this.form = element;
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this.phoenix = phoenix;
      this.core = phoenix.form(options.mainSelector);
      this.ui = phoenix.UI;

      if (!this.core) {
        throw new Error('PhoenixGrid is dependent on PhoenixForm');
      }

      if (!this.ui) {
        throw new Error('PhoenixGrid is dependent on PhoenixUI');
      }

      var selector = this.options.selector;

      this.searchContainer = this.form.find(selector.search.container);
      //this.searchButton = this.form.find(selector.search.button);
      this.searchClearButton = this.form.find(selector.search.clearButton);
      this.filterContainer = this.form.find(selector.filter.container);
      this.filterButton = this.form.find(selector.filter.button);
      this.sortButtons = this.form.find(selector.sort.button);

      this.registerEvents();
    }

    /**
     * Start this object and events.
     */


    _createClass(PhoenixGridElement, [{
      key: 'registerEvents',
      value: function registerEvents() {
        var _this2 = this;

        this.searchClearButton.click(function () {
          _this2.searchContainer.find('input, textarea, select').val('');
          _this2.filterContainer.find('input, textarea, select').val('');

          _this2.form.submit();
        });

        this.filterButton.click(function (event) {
          _this2.toggleFilter();
          event.stopPropagation();
          event.preventDefault();
        });

        this.sortButtons.click(function (event) {
          self.sort(event.currentTarget, event);
        });
      }

      /**
       * Toggle filter bar.
       *
       * @returns {PhoenixGridElement}
       */

    }, {
      key: 'toggleFilter',
      value: function toggleFilter() {
        this.ui.toggleFilter(this.filterContainer, this.filterButton);

        return this;
      }

      /**
       * Sort two items.
       *
       * @param {string} ordering
       * @param {string} direction
       *
       * @returns {boolean}
       */

    }, {
      key: 'sort',
      value: function sort(ordering, direction) {
        var orderingInput = this.form.find('input[name=list_ordering]');

        if (!orderingInput.length) {
          orderingInput = $('<input name="list_ordering" type="hidden" value="" />');

          this.form.append(orderingInput);
        }

        var directionInput = this.form.find('input[name=list_direction]');

        if (!directionInput.length) {
          directionInput = $('<input name="list_direction" type="hidden" value="">');

          this.form.append(directionInput);
        }

        orderingInput.val(ordering);
        directionInput.val(direction);

        return this.core.put();
      }

      /**
       * Check a row's checkbox.
       *
       * @param {number}  row
       * @param {boolean} value
       */

    }, {
      key: 'checkRow',
      value: function checkRow(row) {
        var value = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;

        var ch = this.form.find('input.grid-checkbox[data-row-number=' + row + ']');

        if (!ch.length) {
          throw new Error('Checkbox of row: ' + row + ' not found.');
        }

        ch[0].checked = value;
      }

      /**
       * Update a row.
       *
       * @param  {number} row
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'updateRow',
      value: function updateRow(row, url, queries) {
        this.toggleAll(false);

        this.checkRow(row);

        return this.core.patch(url, queries);
      }

      /**
       * Update a row with batch task.
       *
       * @param  {string} task
       * @param  {number} row
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'doTask',
      value: function doTask(task, row, url, queries) {
        queries = queries || {};

        queries.task = task;

        return this.updateRow(row, url, queries);
      }

      /**
       * Batch update items.
       *
       * @param  {string} task
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'batch',
      value: function batch(task, url, queries) {
        queries = queries || {};

        queries.task = task;

        return this.core.patch(url, queries);
      }

      /**
       * Copy a row.
       *
       * @param  {number} row
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'copyRow',
      value: function copyRow(row, url, queries) {
        this.toggleAll(false);

        this.checkRow(row);

        return this.core.post(url, queries);
      }

      /**
       * Delete checked items.
       *
       * @param  {string} message
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'deleteList',
      value: function deleteList(message, url, queries) {
        var _this3 = this;

        message = message == null ? this.phoenix.__('phoenix.message.delete.confirm') : message;

        if (message !== false) {
          this.phoenix.confirm(message, function (isConfirm) {
            if (isConfirm) {
              _this3.core['delete'](url, queries);
            }
          });
        } else {
          this.core['delete'](url, queries);
        }

        return true;
      }

      /**
       * Delete an itme.
       *
       * @param  {number} row
       * @param  {string} msg
       * @param  {string} url
       * @param  {Object} queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'deleteRow',
      value: function deleteRow(row, msg, url, queries) {
        var _this4 = this;

        msg = msg || this.phoenix.__('phoenix.message.delete.confirm');

        this.phoenix.confirm(msg, function (isConfirm) {
          if (isConfirm) {
            _this4.toggleAll(false);

            _this4.checkRow(row);

            _this4.deleteList(false, url, queries);
          }
        });

        return true;
      }

      /**
       * Toggle all checkboxes.
       *
       * @param  {boolean}          value     Checked or unchecked.
       * @param  {number|boolean}   duration  Duration to check all.
       */

    }, {
      key: 'toggleAll',
      value: function toggleAll(value) {
        var duration = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

        var checkboxes = this.form.find('input.grid-checkbox[type=checkbox]');

        $.each(checkboxes, function (i, e) {
          if (duration) {
            // A little pretty effect
            setTimeout(function () {
              e.checked = value;
            }, duration / checkboxes.length * i);
          } else {
            e.checked = value;
          }
        });

        return this;
      }

      /**
       * Count checked checkboxes.
       *
       * @returns {int}
       */

    }, {
      key: 'countChecked',
      value: function countChecked() {
        return this.getChecked().length;
      }

      /**
       * Get Checked boxes.
       *
       * @returns {Element[]}
       */

    }, {
      key: 'getChecked',
      value: function getChecked() {
        var checkboxes = this.form.find('input.grid-checkbox[type=checkbox]'),
            result = [];

        $.each(checkboxes, function (i, e) {
          if (e.checked) {
            result.push(e);
          }
        });

        return result;
      }

      /**
       * Validate there has one or more checked boxes.
       *
       * @param   {string}  msg
       * @param   {Event}   event
       *
       * @returns {PhoenixGridElement}
       */

    }, {
      key: 'hasChecked',
      value: function hasChecked(msg, event) {
        msg = msg || Phoenix.Translator.translate('phoenix.message.grid.checked');

        if (!this.countChecked()) {
          alert(msg);

          // If you send event object as second argument, we will stop all actions.
          if (event) {
            event.stopPropagation();
            event.preventDefault();
          }

          throw new Error(msg);
        }

        return this;
      }

      /**
       * Reorder all.
       *
       * @param   {string}  url
       * @param   {Object}  queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'reorderAll',
      value: function reorderAll(url, queries) {
        var self = this;
        var origin = this.form.find('input[name=origin_ordering]');

        // If origin exists, we diff them and only send changed group.
        if (origin.length) {
          var originOrdering = origin.val().split(',');
          var inputs = this.form.find('.ordering-control input');

          this.toggleAll(false);

          inputs.each(function (i) {
            var $this = $(this);

            if ($this.val() !== originOrdering[i]) {
              // Check self
              self.checkRow($this.data('order-row'));

              var tr = $this.parents('tr');
              var group = tr.data('order-group');

              // Check same group boxes
              if (group !== '') {
                tr.siblings('[data-order-group=' + group + ']').find('input.grid-checkbox').prop('checked', true);
              }
            }
          });
        }

        return this.batch('reorder', url, queries);
      }

      /**
       * Reorder items.
       *
       * @param  {int}     row
       * @param  {int}     delta
       * @param  {string}  url
       * @param  {Object}  queries
       *
       * @returns {boolean}
       */

    }, {
      key: 'reorder',
      value: function reorder(row, delta, url, queries) {
        queries = queries || {};
        queries.delta = delta;

        return this.doTask('reorder', row, url, queries);
      }
    }]);

    return PhoenixGridElement;
  }();

  window.PhoenixGrid = PhoenixGrid;
})(jQuery);
//# sourceMappingURL=grid.js.map
