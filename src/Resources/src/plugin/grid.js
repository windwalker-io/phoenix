/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * PhoenixGrid
 */
;(function($) {
  "use strict";

  class PhoenixGrid extends PhoenixJQueryPlugin {
    static get is() { return 'Grid'; }

    static get proxies() {
      return {
        grid: 'createPlugin'
      };
    }

    /**
     * Plugin name.
     * @returns {string}
     */
    static get pluginName() { return 'grid' }

    static get pluginClass() { return PhoenixGridElement };

    /**
     * Default options.
     * @returns {Object}
     */
    static get defaultOptions() { 
      return {};
    }
  }
  
  class PhoenixGridElement {
    static get defaultOptions() {
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
      }
    }

    constructor(element, options, phoenix) {
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

      const selector = this.options.selector;

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
    registerEvents() {
      this.searchClearButton.click(() => {
        this.searchContainer.find('input, textarea, select').val('');
        this.filterContainer.find('input, textarea, select').val('');

        this.form.submit();
      });

      this.filterButton.click(function(event) {
        self.toggleFilter();
        event.stopPropagation();
        event.preventDefault();
      });

      this.sortButtons.click(function(event) {
        self.sort(this, event);
      });
    }

    /**
     * Toggle filter bar.
     *
     * @returns {PhoenixGridElement}
     */
    toggleFilter() {
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
    sort(ordering, direction) {
      let orderingInput = this.form.find('input[name=list_ordering]');

      if (!orderingInput.length) {
        orderingInput = $('<input name="list_ordering" type="hidden" value="" />');

        this.form.append(orderingInput);
      }

      let directionInput = this.form.find('input[name=list_direction]');

      if (!directionInput.length) {
        directionInput = $('<input name="list_direction" type="hidden" value="" />');

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
    checkRow(row, value = true) {
      const ch = this.form.find('input.grid-checkbox[data-row-number=' + row + ']');

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
    updateRow(row, url, queries) {
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
    doTask(task, row, url, queries) {
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
    batch(task, url, queries) {
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
    copyRow(row, url, queries) {
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
    deleteList(message, url, queries) {
      message = message || this.phoenix.__('phoenix.message.delete.confirm');

      this.phoenix.confirm(message, isConfirm => {
        if (isConfirm) {
          this.core['delete'](url, queries);
        }
      });

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
    deleteRow(row, msg, url, queries) {
      this.toggleAll(false);

      this.checkRow(row);

      return this.deleteList(msg, url, queries);
    }

    /**
     * Toggle all checkboxes.
     *
     * @param  {boolean}          value     Checked or unchecked.
     * @param  {number|boolean}   duration  Duration to check all.
     */
    toggleAll(value, duration = 100) {
      const checkboxes = this.form.find('input.grid-checkbox[type=checkbox]');

      $.each(checkboxes, function(i, e) {
        if (duration) {
          // A little pretty effect
          setTimeout(function() {
            e.checked = value;
          }, (duration / checkboxes.length) * i);
        }
        else {
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
    countChecked() {
      return this.getChecked().length;
    }

    /**
     * Get Checked boxes.
     *
     * @returns {Element[]}
     */
    getChecked() {
      const checkboxes = this.form.find('input.grid-checkbox[type=checkbox]'),
        result = [];

      $.each(checkboxes, function(i, e) {
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
    hasChecked(msg, event) {
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
    reorderAll(url, queries) {
      const self = this;
      const origin = this.form.find('input[name=origin_ordering]');

      // If origin exists, we diff them and only send changed group.
      if (origin.length) {
        const originOrdering = origin.val().split(',');
        const inputs = this.form.find('.ordering-control input');

        this.toggleAll(false);

        inputs.each(function(i) {
          const $this = $(this);

          if ($this.val() !== originOrdering[i]) {
            // Check self
            self.checkRow($this.data('order-row'));

            const tr = $this.parents('tr');
            const group = tr.data('order-group');

            // Check same group boxes
            if (group !== '') {
              tr.siblings('[data-order-group=' + group + ']')
                .find('input.grid-checkbox')
                .prop('checked', true);
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
    reorder(row, delta, url, queries) {
      queries = queries || {};
      queries.delta = delta;

      return this.doTask('reorder', row, url, queries);
    }
  }

  window.PhoenixGrid = PhoenixGrid;

})(jQuery);
