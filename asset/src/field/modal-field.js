/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

// Phoenix.Field.Modal
$(() => {
  window.Phoenix = window.Phoenix || {};
  window.Phoenix.Field = window.Phoenix.Field || {};

  window.Phoenix.Field.Modal = {
    elements: {},
    select(selector, value, title) {
      const ele = $(selector);

      if (typeof value !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      ele.find('.input-group input').attr('value', value.title).trigger('change').delay(250).effect('highlight');
      ele.find('input[data-value-store]').attr('value', value.value).trigger('change');
      ele.find('.modal-field-image-preview').css('background-image', `url(${value.image})`);

      $('#phoenix-iframe-modal').modal('hide');
    },
    selectAsTag(selector, value, title) {
      const ele = $(selector);
      const select = ele.find('[data-value-store]');

      if (typeof value !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      const selected = select.find(`option[value='${value.value}']`);

      if (selected.length) {
        if (selected.is(':checked')) {
          alert(Phoenix.__('phoenix.form.field.modal.already.selected'));
        } else {
          selected.prop('selected', true).trigger('change');

          $('#phoenix-iframe-modal').modal('hide');
        }
      } else {
        const newOption = new Option(value.title, value.value, true, true);
        select.append(newOption).trigger('change');

        $('#phoenix-iframe-modal').modal('hide');
      }
    },
    selectAsList(selector, value, title) {
      const ele = $(selector);
      const modalList = ele.data('modal-list');

      if (typeof value !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      if (ele.find(`[data-value='${value.value}']`).length === 0) {
        modalList.append(value, true);

        $('#phoenix-iframe-modal').modal('hide');
      } else {
        alert(Phoenix.__('phoenix.form.field.modal.already.selected'));
      }
    }
  };

  window.Phoenix.Field.ModalList = class {
    constructor(selector, items = [], options = {}) {
      this.$ele = $(selector);
      this.options = options;
      this.items = items;
      this.template = underscore.template($(this.options.itemTemplate).html());
      this.render();

      this.$ele.data('modal-list', this);
    }

    render() {
      this.items.forEach((item) => {
        this.append(item);
      });
    }

    append(item, highlight = false) {
      const list = this.$ele.find('.modal-list-container');

      const itemHtml = $(this.template(item));

      itemHtml.attr('data-value', item.value);

      itemHtml.find('.modal-list-item-delete button').on('click', (e) => {
        const $btn = $(e.currentTarget);

        const item = $btn.parents('.list-group-item');

        item.slideUp(400, () => {
          item.remove();
          this.toggleRequired();
        });
      });

      list.append(itemHtml);
      this.toggleRequired();

      if (highlight) {
        itemHtml.effect('highlight');
      }
    }

    toggleRequired() {
      const items = this.$ele.find('[data-value-store]');
      const placeholder = this.$ele.find('[data-validation-placeholder]');

      placeholder.attr('disabled', items.length !== 0);
    }

    open(event) {
      event.preventDefault();
      event.stopPropagation();

      const max = this.$ele.attr('data-max-items');

      if (!max) {
        Phoenix.Modal.open($(event.currentTarget).attr('href'));
        return;
      }

      if ($('.modal-list-container .list-group-item').length >= max) {
        alert(
          Phoenix.__('phoenix.form.field.modal.max.selected', max)
        );

        return;
      }

      Phoenix.Modal.open($(event.currentTarget).attr('href'));
    }
  }
});
