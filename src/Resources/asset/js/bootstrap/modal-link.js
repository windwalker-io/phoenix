"use strict";

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2020 .
 * @license    __LICENSE__
 */
$(function () {
  var ModalLink = /*#__PURE__*/function () {
    function ModalLink(element, modalSelector) {
      var _this = this;

      var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : [];

      _classCallCheck(this, ModalLink);

      _defineProperty(this, "options", {});

      var self = this;
      this.modal = $(modalSelector);
      this.iframe = this.modal.find('iframe');
      this.options = $.extend(true, {}, this.options, options);
      this.modal.on('hide.bs.modal', function () {
        self.iframe.attr('src', '');
      });
      element.on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();
        var options = {};
        options.resize = event.currentTarget.dataset.resize;
        options.size = event.currentTarget.dataset.size;
        options = $.extend(true, {}, _this.options, options);
        console.log(options, _this.options);

        _this.open(event.currentTarget.href, options);
      });
    }

    _createClass(ModalLink, [{
      key: "open",
      value: function open(href) {
        var _this2 = this;

        var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};

        if (options.resize != null) {
          var _onload;

          this.iframe.on('load', _onload = function onload() {
            _this2.resize(_this2.iframe[0]);

            _this2.iframe.off('load', _onload);
          });
        }

        if (options.size != null) {
          this.modal.find('.modal-dialog').removeClass('modal-lg modal-xl modal-sm modal-xs').addClass(options.size);
        }

        this.iframe.css('height', '');
        this.iframe.attr('src', href);
        this.modal.modal('show');
      }
    }, {
      key: "close",
      value: function close() {
        this.modal.modal('hide');
        this.iframe.attr('src', '');
      }
    }, {
      key: "resize",
      value: function resize(iframe) {
        var height = iframe.contentWindow.document.documentElement.scrollHeight;

        if (height < 500) {
          return;
        }

        iframe.style.height = height + 'px';
      }
    }]);

    return ModalLink;
  }();

  Phoenix.plugin('modalLink', ModalLink);
});
//# sourceMappingURL=modal-link.js.map
