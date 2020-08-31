/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(() => {
  window.RepeatableField = {
    name: 'repeatable-field',
    data: {
      control: '',
      id: '',
      items: [],
      fields: [],
      ensureFirstRow: 0,
      singleArray: false,
      hasKey: false,
      attrs: {}
    },
    created() {
      this.items.map(this.prepareItem);

      if (this.ensureFirstRow) {
        this.makeSureDefaultItem();
      }
    },
    mounted() {
      this.singleArray = this.$el.dataset.singleArray === '1';
      this.hasKey = this.$el.dataset.hasKey === '1';
    },
    methods: {
      getId(i, item, field) {
        return `${this.id}-${item.__key}-${field}`;
      },

      getName(i, item, field) {
        if (this.singleArray) {
          if (this.hasKey) {
            if (field === 'key') {
              return '';
            }

            return `${this.control}[${item.key}]`;
          }

          return `${this.control}[]`;
        }

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

          if (this.ensureFirstRow) {
            this.makeSureDefaultItem();
          }
        });
      },

      prepareItem(item) {
        if (!item.__key) {
          item.__key = Phoenix.uniqid();
        }

        if (this.attrs.disabled) {
          item.__disabled = this.attrs.disabled;
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
    },

    computed: {
      //
    }
  };
})();
