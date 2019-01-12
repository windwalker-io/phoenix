/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

$(() => {
  Phoenix.plugin('repeatable', class RepeatableField {
    constructor($element, fields = [], items = [], options = {}) {
      this.$element = $element;
      this.$wrapper = $element.closest('.phoenix-repeatable');
      this.$body = this.$wrapper.find('table tbody');
      this.tmpl = _.template(this.$wrapper.find('.repeatable-item-tmpl').html());
      this.options = options;
      this.fields = fields;
      this.items = items;

      if (this.items.length === 0) {
        this.items.push(this.getEmptyItem());
      }

      this.bindEvents();

      this.render();
    }

    bindEvents() {
      this.$body.on('click', '[data-task="repeatable.add"]', (e) => {
        const current = $(e.currentTarget).closest('[data-repeatable-item]');

        const row = this.getItemHtml(this.getEmptyItem());
        current.after(row);

        row.hide();
        row.fadeIn();
      });

      this.$body.on('click', '[data-task="repeatable.delete"]', (e) => {
        const item = $(e.currentTarget).closest('[data-repeatable-item]');

        item.fadeOut(400, () => {
          item.remove();
          this.toggleRequired();

          if (!this.getItems().length) {
            this.addItem(this.getEmptyItem());
          }
        });
      });
    }

    render() {
      this.items.forEach((item) => {
        const row = $(this.tmpl(item));

        this.$body.append(row);
      });
    }

    prepareItem($elements) {
      $elements.find('[data-task="repeatable.add"]').on('click', () => {

      });
    }

    addItem(item) {
      this.$body.append(this.getItemHtml(item));
    }

    getItemHtml(item) {
      const row = $(this.tmpl(item));

      this.prepareItem(row);

      return row;
    }

    getEmptyItem() {
      const item = {};

      this.fields.forEach(key => {
        item[key] = '';
      });

      item.i = Phoenix.uniqid();

      return item;
    }

    getItems() {
      return this.$body.find('[data-repeatable-item]');
    }

    toggleRequired() {
      //
    }
  })
});
