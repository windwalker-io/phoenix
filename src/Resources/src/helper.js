/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(($) => {
  class PhoenixHelper extends PhoenixPlugin {
    static get is() { return 'Helper'; }

    static get proxies() {
      return {
        confirm: 'confirm',
        keepAlive: 'keepAlive',
        stopKeepAlive: 'stopKeepAlive',
        loadScript: 'loadScript'
      };
    }

    static get defaultOptions() {
      return {}
    }

    constructor() {
      super();

      this.aliveHandle = null;
    }

    /**
     * Confirm popup.
     *
     * @param {string}   message
     * @param {Function} callback
     */
    confirm(message, callback) {
      message = message || 'Are you sure?';

      const confirmed = confirm(message);

      callback(confirmed);

      return confirmed;
    }

    loadScript(urls) {
      if (typeof urls === 'string') {
        urls = [urls];
      }

      const promises = [];
      const data = {};
      data[this.phoenix.asset('version')] = '1';

      urls.forEach(url => {
        promises.push(
          $.getScript({
            url: this.addUriBase(url),
            cache: true,
            data
          })
        )
      });

      return $.when(...promises);
    }

    addUriBase(uri, type = 'path') {
      if (uri.substr(0, 2) === '/' || uri.substr(0, 4) === 'http') {
        return uri;
      }

      return this.phoenix.asset(type) + '/' + uri;
    }

    /**
     * Keep alive.
     *
     * @param {string} url
     * @param {Number} time
     *
     * @return {number}
     */
    keepAlive(url, time) {
      return this.aliveHandle = window.setInterval(() => $.get('/'), time);
    }

    /**
     * Stop keep alive
     */
    stopKeepAlive() {
      clearInterval(this.aliveHandle);
    }
  }

  window.PhoenixHelper = PhoenixHelper;
})(jQuery);
