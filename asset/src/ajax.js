/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

(function ($) {
  'use strict';

  class PhoenixAjax extends PhoenixPlugin {
    static get is() { return 'Ajax'; }

    static get proxies() {
      return {};
    }

    static get defaultOptions() {
      return {};
    }

    constructor() {
      super();

      this.$ = $;

      this.config = {
        customMethod: false,
      };

      this.data = {};

      this.headers = {
        GET: {},
        POST: {},
        PUT: {},
        PATCH: {},
        DELETE: {},
        HEAD: {},
        OPTIONS: {},
        _global: {},
      };
    }

    ready() {
      super.ready();

      this.headers._global['X-CSRF-Token'] = this.phoenix.data('csrf-token');
    }

    /**
     * Send a GET request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    get(url, data, headers, options) {
      return this.request('GET', url, data, headers, options);
    }

    /**
     * Send a POST request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    post(url, data, headers, options) {
      return this.request('POST', url, data, headers, options);
    }

    /**
     * Send a PUT request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    put(url, data, headers, options) {
      return this.request('PUT', url, data, headers, options);
    }

    /**
     * Send a PATCH request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    patch(url, data, headers, options) {
      return this.request('PATCH', url, data, headers, options);
    }

    /**
     * Send a DELETE request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    sendDelete(url, data, headers, options) {
      return this.delete(url, data, headers, options);
    }

    /**
     * Send a DELETE request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    'delete'(url, data, headers, options) {
      return this.request('DELETE', url, data, headers, options);
    }

    /**
     * Send a HEAD request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    head(url, data, headers, options) {
      return this.request('HEAD', url, data, headers, options);
    }

    /**
     * Send a OPTIONS request.
     *
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    options(url, data, headers, options) {
      return this.request('OPTIONS', url, data, headers, options);
    }

    /**
     * Send request.
     *
     * @param {string} method
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    sendRequest(method, url, data, headers, options) {
      return this.request(method, url, data, headers, options);
    }

    /**
     * Send request.
     *
     * @param {string} method
     * @param {string} url
     * @param {Object} data
     * @param {Object} headers
     * @param {Object} options
     *
     * @returns {jqXHR}
     */
    request(method, url = '', data = {}, headers = {}, options = {}) {
      let reqOptions = options;
      let reqUrl = url;
      let reqHeaders = headers;

      if (typeof reqUrl === 'object') {
        reqOptions = reqUrl;
        reqUrl = reqOptions.url;
      }

      const isFormData = data instanceof FormData;

      if (isFormData) {
        reqOptions.processData = false;
        reqOptions.contentType = false;
      }

      if (typeof reqOptions.dataType === 'undefined') {
        reqOptions.dataType = 'json';
      }

      reqOptions.data = typeof data === 'string' || isFormData
        ? data
        : $.extend(true, {}, this.data, reqOptions.data, data);

      reqOptions.type = method.toUpperCase() || 'GET';
      const { type } = reqOptions;

      if (['POST', 'GET'].indexOf(reqOptions.type) === -1 && this.config.customMethod) {
        reqHeaders['X-HTTP-Method-Override'] = reqOptions.type;
        reqOptions.data._method = reqOptions.type;
        reqOptions.type = 'POST';
      }

      reqOptions.headers = $.extend(
        true,
        {},
        this.headers._global,
        this.headers[type],
        reqOptions.headers,
        reqHeaders,
      );

      return this.$.ajax(reqUrl, reqOptions)
        .fail((xhr, error) => {
          if (error === 'parsererror') {
            // eslint-disable-next-line no-param-reassign
            xhr.statusText = 'Unable to parse data.';
          } else {
            xhr.statusText = decodeURIComponent(xhr.statusText);
          }
        });
    }

    /**
     * Set custom method with _method parameter.
     *
     * This method will return a clone of this object to help us send request once.
     *
     * @returns {PhoenixAjax}
     */
    customMethod() {
      const clone = $.extend(true, {}, this);

      clone.config.customMethod = true;

      return clone;
    }
  }

  window.PhoenixAjax = PhoenixAjax;
}(jQuery));
