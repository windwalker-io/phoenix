"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */
$(function () {
  Phoenix.plugin('repeatable', /*#__PURE__*/function () {
    function RepeatableField($element) {
      var fields = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var items = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];
      var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};

      _classCallCheck(this, RepeatableField);

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

    _createClass(RepeatableField, [{
      key: "bindEvents",
      value: function bindEvents() {
        var _this = this;

        this.$body.on('click', '[data-task="repeatable.add"]', function (e) {
          var current = $(e.currentTarget).closest('[data-repeatable-item]');

          var row = _this.getItemHtml(_this.getEmptyItem());

          current.after(row);
          row.hide();
          row.fadeIn();
        });
        this.$body.on('click', '[data-task="repeatable.delete"]', function (e) {
          var item = $(e.currentTarget).closest('[data-repeatable-item]');
          item.fadeOut(400, function () {
            item.remove();

            _this.toggleRequired();

            if (!_this.getItems().length) {
              _this.addItem(_this.getEmptyItem());
            }
          });
        });
      }
    }, {
      key: "render",
      value: function render() {
        var _this2 = this;

        this.items.forEach(function (item) {
          var row = $(_this2.tmpl(item));

          _this2.$body.append(row);
        });
      }
    }, {
      key: "prepareItem",
      value: function prepareItem($elements) {
        $elements.find('[data-task="repeatable.add"]').on('click', function () {});
      }
    }, {
      key: "addItem",
      value: function addItem(item) {
        this.$body.append(this.getItemHtml(item));
      }
    }, {
      key: "getItemHtml",
      value: function getItemHtml(item) {
        var row = $(this.tmpl(item));
        this.prepareItem(row);
        return row;
      }
    }, {
      key: "getEmptyItem",
      value: function getEmptyItem() {
        var item = {};
        this.fields.forEach(function (key) {
          item[key] = '';
        });
        item.i = Phoenix.uniqid();
        return item;
      }
    }, {
      key: "getItems",
      value: function getItems() {
        return this.$body.find('[data-repeatable-item]');
      }
    }, {
      key: "toggleRequired",
      value: function toggleRequired() {//
      }
    }]);

    return RepeatableField;
  }());
});
//# sourceMappingURL=repeatable.bak.js.map
