/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(() => {
  class PhoenixLegacy extends PhoenixPlugin {
    static get is() {
      return 'Legacy';
    }

    created() {
      const phoenix = this.phoenix;

      phoenix.Theme = phoenix.UI;

      let formInited = false;
      let gridInited = false;

      phoenix.on('jquery.plugin.created', event => {
        const debug = this.phoenix.data('windwalker.debug');

        // Legacy Form polyfill
        if (!formInited && event.name === 'form') {
          ['delete', 'get', 'patch', 'post', 'put', 'sendDelete', 'submit'].forEach((method) => {
            phoenix[method] = (...args) => {
              debug ? this.constructor.warn('Phoenix', method) : null;
              return event.instance[method](...args);
            }
          });

          formInited = true;
        }

        // Legacy Grid polyfill
        if (!gridInited && event.name === 'grid') {
          ['toggleFilter', 'sort', 'checkRow', 'updateRow', 'doTask', 'batch', 'copyRow', 'deleteList', 'deleteRow',
            'toggleAll', 'countChecked', 'getChecked', 'hasChecked', 'reorderAll', 'reorder']
            .forEach((method) => {
              phoenix.Grid[method] = (...args) => {
                debug ? this.constructor.warn('Phoenix.Grid', method) : null;
                return event.instance[method](...args);
              }
            });

          gridInited = true;
        }
      });
    }

    ready() {
      super.ready();
    }

    static warn(obj, method) {
      console.warn(`Calling ${obj}.${method}() is deprecated.`);
    }
  }

  window.PhoenixLegacy = PhoenixLegacy;
})();
