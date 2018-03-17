'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

/**
 * Phoenix.Router
 */
(function () {
  "use strict";

  var PhoenixRouter = function (_PhoenixPlugin) {
    _inherits(PhoenixRouter, _PhoenixPlugin);

    _createClass(PhoenixRouter, null, [{
      key: 'is',
      get: function get() {
        return 'Router';
      }
    }, {
      key: 'proxies',
      get: function get() {
        return {
          addRoute: 'add',
          route: 'route'
        };
      }
    }]);

    function PhoenixRouter() {
      _classCallCheck(this, PhoenixRouter);

      var _this = _possibleConstructorReturn(this, (PhoenixRouter.__proto__ || Object.getPrototypeOf(PhoenixRouter)).call(this));

      _this.routes = {};
      return _this;
    }

    /**
     * Add a route.
     *
     * @param route
     * @param url
     *
     * @returns {PhoenixRouter}
     */


    _createClass(PhoenixRouter, [{
      key: 'add',
      value: function add(route, url) {
        this.routes[route] = url;

        return this;
      }

      /**
       * Get route.
       *
       * @param route
       *
       * @returns {String}
       */

    }, {
      key: 'route',
      value: function route(_route) {
        if (this.routes[_route] === undefined) {
          throw new Error('Route: "' + _route + '" not found');
        }

        return this.routes[_route];
      }
    }]);

    return PhoenixRouter;
  }(PhoenixPlugin);

  window.PhoenixRouter = PhoenixRouter;
})();
//# sourceMappingURL=router.js.map
