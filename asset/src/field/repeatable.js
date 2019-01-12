/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(() => {
  window.RepeatableField = {
    data: {
      control: '',
      id: '',
      items: [],
      fields: [],
    },
    created() {
      this.items.map(this.prepareItem);

      this.makeSureDefaultItem();
    },
    mounted() {
      //
    },
    methods: {
      getId(i, item, field) {
        return `${this.id}-${item.__key}-${field}`;
      },

      getName(i, item, field) {
        return `${this.control}[${i}][${field}]`;
      },

      addItem(i) {
        this.items.splice(i + 1, 0, this.prepareItem(this.getEmptyItem()));
      },

      delItem(i) {
        this.items.splice(i, 1);

        this.makeSureDefaultItem();
      },

      prepareItem(item) {
        if (!item.__key) {
          item.__key = Phoenix.uniqid();
        }

        return item;
      },

      getEmptyItem() {
        return $.extend({}, this.fields);
      },

      makeSureDefaultItem() {
        if (this.items.length === 0) {
          this.items.push(this.prepareItem(this.getEmptyItem()));
        }
      }
    }
  };
})();
