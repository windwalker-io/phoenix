/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2020 .
 * @license    __LICENSE__
 */

$(() => {
  class ModalLink {
    options = {};

    constructor (
      element,
      modalSelector,
      options = []
    ) {
      this.modal = $(modalSelector);
      this.iframe = this.modal.find('iframe');
      this.options = $.extend(true, {}, this.options, options);

      this.iframe[0].modalLink = () => {
        return this;
      };

      this.modal.on('hide.bs.modal', () => {
        this.iframe.attr('src', '');
      });

      element.on('click', (event) => {
        event.stopPropagation();
        event.preventDefault();

        let options = {};
        options.resize = event.currentTarget.dataset.resize;
        options.size = event.currentTarget.dataset.size;

        options = $.extend(true, {}, this.options, options);
        this.open(event.currentTarget.href, options);
      });

      // B/C
      if (modalSelector === '#phoenix-iframe-modal') {
        Phoenix.Modal = this;
      }
    }

    open(href, options = {}) {
      if (options.resize != null) {
        let onload;
        this.iframe.on('load', onload = () => {
          this.resize(this.iframe[0]);

          this.iframe.off('load', onload);
        });
      }

      if (options.size != null) {
        this.modal
          .find('.modal-dialog')
          .removeClass('modal-lg modal-xl modal-sm modal-xs')
          .addClass(options.size);
      }

      this.iframe.css('height', '');
      this.iframe.attr('src', href);
      this.modal.modal('show');
    }

    close() {
      this.modal.modal('hide');
      this.iframe.attr('src', '');
    }

    resize(iframe) {
      const height = iframe.contentWindow.document.documentElement.scrollHeight;

      if (height < 500) {
        return;
      }

      iframe.style.height = height + 'px';
    }
  }

  Phoenix.plugin('modalLink', ModalLink);
});
