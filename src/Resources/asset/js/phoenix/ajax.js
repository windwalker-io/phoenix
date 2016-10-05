/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

var Phoenix;
(function ($, Phoenix)
{
    "use strict";

    Phoenix.Ajax = {
        $: $,

        config: {
            customMethod: false,
        },

        data: {},

        headers: {
            GET: {},
            POST: {},
            PUT: {},
            PATCH: {},
            DELETE: {},
            HEAD: {},
            OPTIONS: {},
            _global: {}
        },

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
        get: function (url, data, headers, options)
        {
            return this.request('GET', url, data, headers, options);
        },

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
        post: function (url, data, headers, options)
        {
            return this.request('POST', url, data, headers, options);
        },

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
        put: function (url, data, headers, options)
        {
            return this.request('PUT', url, data, headers, options);
        },

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
        patch: function (url, data, headers, options)
        {
            return this.request('PATCH', url, data, headers, options);
        },

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
        sendDelete: function (url, data, headers, options)
        {
            return this.request('DELETE', url, data, headers, options);
        },

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
        head: function (url, data, headers, options)
        {
            return this.request('HEAD', url, data, headers, options);
        },

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
        options: function (url, data, headers, options)
        {
            return this.request('OPTIONS', url, data, headers, options);
        },

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
        request: function (method, url, data, headers, options)
        {
            options = options || {};
            headers = headers || {};
            data = data || {};

            if (typeof url == 'object')
            {
                options = url;
                url = options.url;
            }

            if (url === undefined)
            {
                throw new Error('No URL provided');
            }

            options.data = $.extend(true, {}, this.data, options.data, data);
            options.type = method.toUpperCase() || 'GET';
            var type = options.type;

            if (['POST', 'GET'].indexOf(options.type) === -1 && this.config.customMethod)
            {
                headers['X-HTTP-Method-Override'] = options.type;
                options.data._method = options.type;
                options.type = 'POST';
            }

            options.headers = $.extend(true, {}, this.headers._global, this.headers[type], options.headers, headers);

            return this.$.ajax(url, options);
        },

        /**
         * Set custom method with _method parameter.
         *
         * This method will return a clone of this object to help us send request once.
         *
         * @returns {Ajax}
         */
        customMethod: function ()
        {
            var clone = $.extend(true, {}, this);

            clone.config.customMethod = true;

            return clone;
        }
    };

    Phoenix.Ajax['delete'] = Phoenix.Ajax.sendDelete;

})(jQuery, Phoenix || (Phoenix = {}));
