/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

var Phoenix;

/**
 * Phoenix.Router
 */
(function(Phoenix)
{
    "use strict";

    Phoenix.Router = {
        routes: {},

        /**
         * Add a route.
         *
         * @param route
         * @param url
         *
         * @returns {Phoenix.Router}
         */
        add: function(route, url)
        {
            this.routes[route] = url;

            return this;
        },

        /**
         * Get route.
         *
         * @param route
         *
         * @returns {String}
         */
        route: function(route)
        {
            if (this.routes[route] === undefined)
            {
                throw new Error('Route: "' + route + '" not found');
            }

            return this.routes[route];
        }
    };

    Phoenix.Uri = {};
})(Phoenix || (Phoenix = {}));
