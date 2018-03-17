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
      this.waits = [];

      // Wait dom ready
      this.wait(resolve => {
        $(() => resolve());
      });

      // Ready
      $(() => {
        this.completed().then(() => this.trigger('loaded'));
      });
    }

    use(plugin) {
      if (Array.isArray(plugin)) {
        plugin.forEach(p => this.use(p));
        return this;
      }

      if (plugin.is === undefined) {
        throw new Error(`Plugin: ${plugin.name} must instance of : ${PhoenixPlugin.name}`);
      }

      const instance = plugin.install(this);
      instance.boot(this);

      this.trigger('plugin.installed', instance);

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

    data(name, value) {
      if (value === undefined) {
        return $(document).data(name);
      }

      $(document).data(name, value);

      return this;
    }

    removeData(name) {
      $(document).removeData(name);

      return this;
    }

    uri(type) {
      return this.data('phoenix.uri')[type];
    }

    asset(type) {
      return this.uri('asset')[type];
    }

    wait(callback) {
      const d = $.Deferred();

      this.waits.push(d);

      callback(() => d.resolve());

      return d;
    }

    completed() {
      const promise = $.when(...this.waits);

      this.waits = [];

      return promise;
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

  window.PhoenixCore = PhoenixCore;
})(jQuery);
