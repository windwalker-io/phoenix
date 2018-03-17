/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

($ => {
  class PhoenixForm extends PhoenixJQueryPlugin {
    static get is() { return 'Form'; }

    static get proxies() {
      return {
        form: 'createPlugin'
      };
    }

    /**
     * Plugin name.
     * @returns {string}
     */
    static get pluginName() { return 'form' }

    static get pluginClass() { return PhoenixFormElement }

    /**
     * Default options.
     * @returns {Object}
     */
    static get defaultOptions() { return {} }
  }

  class PhoenixFormElement {
    /**
     * Constructor.
     * @param {jQuery} $form
     * @param {Object} options
     */
    constructor($form, options) {
      options = $.extend(true, {}, this.constructor.defaultOptions, options);

      this.form = $form;
      this.options = options;
    }

    /**
     * Make a request.
     *
     * @param  {string} url
     * @param  {Object} queries
     * @param  {string} method
     * @param  {string} customMethod
     *
     * @returns {boolean}
     */
    submit(url, queries, method, customMethod) {
      const form = this.form;

      if (customMethod) {
        let methodInput = form.find('input[name="_method"]');

        if (!methodInput.length) {
          methodInput = $('<input name="_method" type="hidden">');

          form.append(methodInput);
        }

        methodInput.val(customMethod);
      }

      // Set queries into form.
      if (queries) {
        let input;

        $.each(queries, function(key, value) {
          input = form.find('input[name="' + key + '"]');

          if (!input.length) {
            input = $('<input name="' + key + '" type="hidden">');

            form.append(input);
          }

          input.val(value);
        });
      }

      if (url) {
        form.attr('action', url);
      }

      if (method) {
        form.attr('method', method);
      }

      form.submit();

      return true;
    }

    /**
     * Make a GET request.
     *
     * @param  {string} url
     * @param  {Object} queries
     * @param  {string} customMethod
     *
     * @returns {boolean}
     */
    get(url, queries, customMethod) {
      return this.submit(url, queries, 'GET', customMethod);
    }

    /**
     * Post form.
     *
     * @param  {string} url
     * @param  {Object} queries
     * @param  {string} customMethod
     *
     * @returns {boolean}
     */
    post(url, queries, customMethod) {
      customMethod = customMethod || 'POST';

      return this.submit(url, queries, 'POST', customMethod);
    }

    /**
     * Make a PUT request.
     *
     * @param  {string} url
     * @param  {Object} queries
     *
     * @returns {boolean}
     */
    put(url, queries) {
      return this.post(url, queries, 'PUT');
    }

    /**
     * Make a PATCH request.
     *
     * @param  {string} url
     * @param  {Object} queries
     *
     * @returns {boolean}
     */
    patch(url, queries) {
      return this.post(url, queries, 'PATCH');
    }

    /**
     * Make a DELETE request.
     *
     * @param  {string} url
     * @param  {Object} queries
     *
     * @returns {boolean}
     */
    sendDelete(url, queries) {
      return this['delete'](url, queries);
    }

    /**
     * Make a DELETE request.
     *
     * @param  {string} url
     * @param  {Object} queries
     *
     * @returns {boolean}
     */
    delete(url, queries) {
      return this.post(url, queries, 'DELETE');
    }
  }

  window.PhoenixForm = PhoenixForm;
})(jQuery);
