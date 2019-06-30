/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * Phoenix.Router
 */
(($) => {
  'use strict';

  class PhoenixRouter extends PhoenixPlugin {
    static get is() { return 'Router'; }

    static get proxies() {
      return {
        addRoute: 'add',
        route: 'route',
      };
    }

    ready() {
      $(window).on('popstate', e => this.phoenix.on('router.popstate', e));
    }

    /**
     * Add a route.
     *
     * @param route
     * @param url
     *
     * @returns {PhoenixRouter}
     */
    add(route, url) {
      const data = {};
      data[route] = url;

      this.phoenix.data('phoenix.routes', data);

      return this;
    }

    /**
     * Get route.
     *
     * @param route
     * @param query
     * @returns {String|PhoenixRouter}
     */
    route(route, query = null) {
      const url = this.phoenix.data('phoenix.routes')[route];

      if (url === undefined) {
        throw new Error(`Route: "${route}" not found`);
      }

      return this.addQuery(url, query);
    }

    has(route) {
      return undefined !== this.phoenix.data('phoenix.routes')[route];
    }

    addQuery(url, query = null) {
      if (query === null) {
        return url;
      }

      const queryString = $.param(query);

      return url + (/\?/.test(url) ? `&${queryString}` : `?${queryString}`);
    }

    push(data) {
      if (typeof data === 'string') {
        // eslint-disable-next-line no-param-reassign
        data = { uri: data };
      }

      window.history.pushState(
        data.state || null,
        data.title || null,
        data.uri || this.route(data.route, data.params),
      );

      return this;
    }

    replace(data) {
      if (typeof data === 'string') {
        // eslint-disable-next-line no-param-reassign
        data = { uri: data };
      }

      window.history.replaceState(
        data.state || null,
        data.title || null,
        data.uri || this.route(data.route, data.params),
      );

      return this;
    }

    state() {
      return window.history.state;
    }

    back() {
      window.history.back();
    }

    forward() {
      window.history.forward();
    }

    go(num) {
      window.history.go(num);
    }
  }

  window.PhoenixRouter = PhoenixRouter;
})(jQuery);
