"use strict";

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */
(function () {
  window.RepeatableField = {
    name: 'repeatable-field',
    data: {
      control: '',
      id: '',
      items: [],
      fields: [],
      ensureFirstRow: 0
    },
    created: function created() {
      this.items.map(this.prepareItem);

      if (this.ensureFirstRow) {
        this.makeSureDefaultItem();
      }
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
        var _this = this;

        this.items.splice(i + 1, 0, this.prepareItem(this.getEmptyItem()));
        this.$nextTick(function () {
          var el = _this.$refs['repeat-item-' + (i + 1)][0];
          $(el).css('display', 'none').fadeIn();
        });
      },
      delItem: function delItem(i) {
        var _this2 = this;

        var el = this.$refs['repeat-item-' + i][0];
        $(el).fadeOut(function () {
          _this2.items.splice(i, 1);

          if (_this2.ensureFirstRow) {
            _this2.makeSureDefaultItem();
          }
        });
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
