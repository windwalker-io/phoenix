"use strict";

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */
(function () {
  window.RepeatableField = {
    data: {
      control: '',
      id: '',
      items: [],
      fields: []
    },
    created: function created() {
      this.items.map(this.prepareItem);
      this.makeSureDefaultItem();
    },
    mounted: function mounted() {//
    },
    methods: {
      getId: function getId(i, item, field) {
        return "".concat(this.id, "-").concat(item.__key, "-").concat(field);
      },
      getName: function getName(i, item, field) {
        return "".concat(this.control, "[").concat(i, "][").concat(field, "]");
      },
      addItem: function addItem(i) {
        this.items.splice(i + 1, 0, this.prepareItem(this.getEmptyItem()));
      },
      delItem: function delItem(i) {
        this.items.splice(i, 1);
        this.makeSureDefaultItem();
      },
      prepareItem: function prepareItem(item) {
        if (!item.__key) {
          item.__key = Phoenix.uniqid();
        }

        return item;
      },
      getEmptyItem: function getEmptyItem() {
        return $.extend({}, this.fields);
      },
      makeSureDefaultItem: function makeSureDefaultItem() {
        if (this.items.length === 0) {
          this.items.push(this.prepareItem(this.getEmptyItem()));
        }
      }
    }
  };
})();
//# sourceMappingURL=repeatable.js.map
