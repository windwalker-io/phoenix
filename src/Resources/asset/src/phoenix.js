/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

($ => {
  class PhoenixCore {
    /**
     * Default options.
     * @returns {Object}
     */
    static get defaultOptions() {
      return {}
    }

    constructor(options = {}) {
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this._listeners = {};
    }

    use(plugin) {
      if (Array.isArray(plugin)) {
        plugin.forEach(p => this.use(p));
        return this;
      }

      if (!plugin instanceof PhoenixPlugin) {
        throw new Error('Plugin must instance of : ' + PhoenixPlugin.name);
      }

      plugin.install(this);

      this.trigger('plugin.installed', plugin);

      return this;
    }

    detach(plugin) {
      if (!plugin instanceof PhoenixPlugin) {
        throw new Error('Plugin must instance of : ' + PhoenixPlugin.name);
      }

      plugin.uninstall(this);

      this.trigger('plugin.uninstalled', plugin);

      return this;
    }

    on(event, handler) {
      if (this._listeners[event] === undefined) {
        this._listeners[event] = [];
      }

      this._listeners[event].push(handler);

      return this;
    }

    off(event) {
      delete this._listeners[event];

      return this;
    }

    trigger(event, args) {
      const r = [];
      this.listeners(event).forEach(listener => {
        r.push(listener(args));
      });

      return r;
    }

    listeners(event) {
      return this._listeners[event] === undefined ? [] : this._listeners[event];
    }

    /**
     * Confirm popup.
     *
     * TODO: Move to core.
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

    /**
     * Add message.
     *
     * TODO: Move to core.
     *
     * @param {string} msg
     * @param {string} type
     *
     * @returns {PhoenixForm}
     */
    addMessage(msg, type) {
      const messageContainer = $(this.options.selector.message);

      Phoenix.Theme.renderMessage(messageContainer, msg, type);

      return this;
    }

    /**
     * Remove all messages.
     *
     * TODO: Move to core.
     *
     * @returns {PhoenixForm}
     */
    removeMessages() {
      const messageContainer = $(this.options.selector.message);

      Phoenix.Theme.removeMessages(messageContainer);

      return this;
    }

    /**
     * Keep alive.
     *
     * TODO: Move to core.
     *
     * @param {string} url
     * @param {Number} time
     *
     * @return {number}
     */
    keepAlive(url, time) {
      return window.setInterval(function () {
        let r;

        try {
          r = new XMLHttpRequest;
        }
        catch (e) {
          // Nothing
        }

        if (r) {
          r.open('GET', url, true);
          r.send(null);
        }
      }, time);
    }

    plugin(name, plugin) {
      const self = this;
      $.fn[name] = function (...args) {
        if (!this.data('phoenix.' + name)) {
          const instance = new plugin(this, ...args);
          this.data('phoenix.' + name, instance);
          self.trigger('jquery.plugin.created', {name: name, ele: this, instance: instance});
        }

        const instance = this.data('phoenix.' + name);

        self.trigger('jquery.plugin.get', {name: name, ele: this, instance: instance});

        return instance;
      };

      return this;
    }
  }

  class PhoenixPlugin {
    static get is() {
      throw new Error('Please add "is" property to Phoenix Plugin: '.this.constructor.name);
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
      const self = new this(phoenix);

      this.createProxies(phoenix, self);
    }

    static uninstall(phoenix) {
      const self = new this(phoenix);

      this.resetProxies(phoenix, self);
    }

    constructor(phoenix) {
      this.phoenix = phoenix;

      const name = this.constructor.is.toLowerCase();

      // Merge to global options
      this.phoenix.options[name] = $.extend(
        true,
        this.phoenix.options[name],
        this.constructor.defaultOptions,
        this.phoenix.options[name]
      );

      // Created hook
      this.created();

      // DOM Ready hook
      $(() => this.ready());

      // Phoenix onload hook
      // todo: add loaded
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
      super.install(phoenix);

      phoenix.plugin(this.pluginName, this.pluginClass);
    }

    createPlugin(selector, options = {}, ...args) {
      options.mainSelector = selector;

      return $(selector)[this.constructor.pluginName](options, this.phoenix, ...args);
    }
  }

  window.Phoenix = new PhoenixCore();
  window.PhoenixPlugin = PhoenixPlugin;
  window.PhoenixJQueryPlugin = PhoenixJQueryPlugin;
})(jQuery);

(($) => {
  class PhoenixLegacy extends PhoenixPlugin {
    static get is() {
      return 'Legacy';
    }

    static install(phoenix) {
      super.install(phoenix);

      phoenix.Theme = phoenix.UI;
    }

    constructor(phoenix) {
      super(phoenix);

      let formInited = false;
      let gridInited = false;

      phoenix.on('jquery.plugin.created', event => {
        // Legacy Form polyfill
        if (!formInited && event.name === 'form') {
          ['delete', 'get', 'patch', 'post', 'put', 'sendDelete', 'submit'].forEach((method) => {
            phoenix[method] = (...args) => event.instance[method](...args);
          });

          formInited = true;
        }

        // Legacy Grid polyfill
        if (!gridInited && event.name === 'grid') {
          ['toggleFilter', 'sort', 'checkRow', 'updateRow', 'doTask', 'batch', 'copyRow', 'deleteList', 'deleteRow',
            'toggleAll', 'countChecked', 'getChecked', 'hasChecked', 'reorderAll', 'reorder']
            .forEach((method) => {
              phoenix.Grid[method] = (...args) => event.instance[method](...args);
            });

          gridInited = true;
        }
      });
    }

    ready() {
      super.ready();
    }
  }

  window.PhoenixLegacy = PhoenixLegacy;
})(jQuery);
