'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

// Phoenix.Field.Modal
$(function () {
  window.Phoenix = window.Phoenix || {};
  window.Phoenix.Field = window.Phoenix.Field || {};

  window.Phoenix.Field.Modal = {
    elements: {},
    select: function select(selector, value, title) {
      var ele = $(selector);

      if ((typeof value === 'undefined' ? 'undefined' : _typeof(value)) !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      ele.find('.input-group input').attr('value', value.title).trigger('change').delay(250).effect('highlight');
      ele.find('input[data-value-store]').attr('value', value.value).trigger('change');

      $('#phoenix-iframe-modal').modal('hide');
    },
    selectAsTag: function selectAsTag(selector, value, title) {
      var ele = $(selector);
      var select = ele.find('[data-value-store]');

      if ((typeof value === 'undefined' ? 'undefined' : _typeof(value)) !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      var selected = select.find('option[value=\'' + value.value + '\']');

      if (selected.length) {
        if (selected.is(':checked')) {
          alert(Phoenix.__('phoenix.form.field.modal.already.selected'));
        } else {
          selected.prop('selected', true).trigger('change');

          $('#phoenix-iframe-modal').modal('hide');
        }
      } else {
        var newOption = new Option(value.title, value.value, true, true);
        select.append(newOption).trigger('change');

        $('#phoenix-iframe-modal').modal('hide');
      }
    },
    selectAsList: function selectAsList(selector, value, title) {
      var ele = $(selector);
      var modalList = ele.data('modal-list');

      if ((typeof value === 'undefined' ? 'undefined' : _typeof(value)) !== 'object') {
        value = {
          value: value,
          title: title
        };
      }

      if (ele.find('[data-value=\'' + value.value + '\']').length === 0) {
        modalList.append(value, true);

        $('#phoenix-iframe-modal').modal('hide');
      } else {
        alert(Phoenix.__('phoenix.form.field.modal.already.selected'));
      }
    }
  };

  window.Phoenix.Field.ModalList = function () {
    function _class(selector) {
      var items = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};

      _classCallCheck(this, _class);

      this.$ele = $(selector);
      this.options = options;
      this.items = items;
      this.template = underscore.template($(this.options.itemTemplate).html());
      this.render();

      this.$ele.data('modal-list', this);
    }

    _createClass(_class, [{
      key: 'render',
      value: function render() {
        var _this = this;

        this.items.forEach(function (item) {
          _this.append(item);
        });
      }
    }, {
      key: 'append',
      value: function append(item) {
        var _this2 = this;

        var highlight = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;

        var list = this.$ele.find('.modal-list-container');

        var itemHtml = $(this.template(item));

        itemHtml.attr('data-value', item.value);

        itemHtml.find('.modal-list-item-delete button').on('click', function (e) {
          var $btn = $(e.currentTarget);

          var item = $btn.parents('.list-group-item');

          item.slideUp(400, function () {
            item.remove();
            _this2.toggleRequired();
          });
        });

        list.append(itemHtml);
        this.toggleRequired();

        if (highlight) {
          itemHtml.effect('highlight');
        }
      }
    }, {
      key: 'toggleRequired',
      value: function toggleRequired() {
        var items = this.$ele.find('[data-value-store]');
        var placeholder = this.$ele.find('[data-validation-placeholder]');
        console.log(items.length);
        placeholder.attr('disabled', items.length !== 0);
      }
    }]);

    return _class;
  }();
});
//# sourceMappingURL=modal-field.js.map
