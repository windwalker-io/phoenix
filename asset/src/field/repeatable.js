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

        this.$nextTick(() => {
          const el = this.$refs['repeat-item-' + (i + 1)][0];

          $(el).css('display', 'none').fadeIn();
        });
      },

      delItem(i) {
        const el = this.$refs['repeat-item-' + i][0];

        $(el).fadeOut(() => {
          this.items.splice(i, 1);

          this.makeSureDefaultItem();
        });
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
