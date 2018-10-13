/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

($ => {
  class PhoenixPlugin {
    static get is() {
      throw new Error('Please add "is" property to Phoenix Plugin: ' + this.name);
    }

    static get proxies() {
      return {};
    }

    static get defaultOptions() {
      return {}
    }

    get options() {
      return this.phoenix.options[this.constructor.is.toLowerCase()];
    }

    static install(phoenix) {
      const self = new this;

      this.createProxies(phoenix, self);
      return self;
    }

    static uninstall(phoenix) {
      const self = new this(phoenix);

      this.resetProxies(phoenix, self);
    }

    constructor() {
      //
    }

    boot(phoenix) {
      this.phoenix = phoenix;

      const name = this.constructor.is.toLowerCase();

      // Merge to global options
      this.phoenix.options[name] = $.extend(
        true,
        {},
        this.constructor.defaultOptions,
        this.phoenix.options[name]
      );

      // Created hook
      this.created();

      // DOM Ready hook
      $(() => this.ready());

      // Phoenix onload hook
      this.phoenix.on('loaded', this.loaded);
    }

    created() {
      //
    }

    ready() {
      //
    }

    loaded() {
      //
    }

    static createProxies(phoenix, plugin) {
      if (plugin.constructor.proxies === undefined) {
        return this;
      }

      this.resetProxies(phoenix, plugin);

      phoenix[plugin.constructor.is] = plugin;

      const proxies = plugin.constructor.proxies;

      for (let name in proxies) {
        if (!proxies.hasOwnProperty(name)) {
          continue;
        }

        const origin = proxies[name];

        if (phoenix[name] !== undefined) {
          throw new Error(`Property: ${name} has exists in Phoenix instance.`);
        }

        if (typeof origin === 'function') {
          phoenix[name] = origin;
        } else if (plugin[origin] !== undefined) {
          if (typeof plugin[origin] === 'function') {
            phoenix[name] = function (...args) {
              return plugin[origin](...args);
            };
          } else {
            Object.defineProperties(phoenix, name, {
              get: () => plugin[origin],
              set: value => {
                plugin[origin] = value;
              }
            });
          }
        } else {
          throw new Error(`Proxy property: "${origin}" not found in Plugin: ${plugin.constructor.name}`);
        }
      }
    }

    static resetProxies(phoenix, plugin) {
      const name = typeof plugin === 'string' ? plugin : plugin.constructor.is;

      if (phoenix[name]) {
        plugin = phoenix[name];
      }

      if (plugin.constructor.proxies === undefined) {
        return;
      }

      for (let name in plugin.constructor.proxies) {
        delete phoenix[name];
      }

      delete phoenix[plugin.constructor.is];
    }
  }

  class PhoenixJQueryPlugin extends PhoenixPlugin {
    /**
     * Plugin name.
     * @returns {string|null}
     */
    static get pluginName() {
      throw new Error('Please provide a plugin name.');
    }

    static get pluginClass() {
      throw new Error('Please provide a class as plugin instance.');
    }

    static install(phoenix) {
      const instance = super.install(phoenix);

      phoenix.plugin(this.pluginName, this.pluginClass);

      return instance;
    }

    createPlugin(selector, options = {}, ...args) {
      options.mainSelector = selector;

      return $(selector)[this.constructor.pluginName](options, this.phoenix, ...args);
    }
  }

  window.PhoenixPlugin = PhoenixPlugin;
  window.PhoenixJQueryPlugin = PhoenixJQueryPlugin;
})(jQuery);
