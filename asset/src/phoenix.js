/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(($) => {
  class PhoenixCore extends mix(class {}).with(PhoenixEventMixin) {
    /**
     * Default options.
     * @returns {Object}
     */
    static get defaultOptions() {
      return {};
    }

    constructor(options = {}) {
      super();
      this.options = $.extend(true, {}, this.constructor.defaultOptions, options);
      this._listeners = {};
      this.waits = [];

      // Wait dom ready
      this.wait((resolve) => {
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
      if (!(plugin instanceof PhoenixPlugin)) {
        throw new Error(`Plugin must instance of : ${PhoenixPlugin.name}`);
      }

      plugin.uninstall(this);

      this.trigger('plugin.uninstalled', plugin);

      return this;
    }

    tap(value, callback) {
      callback(value);

      return value;
    }

    trigger(event, ...args) {
      return this.tap(super.trigger(event, ...args), () => {
        if ($(document).data('windwalker.debug')) {
          console.debug(`[Phoenix Event] ${event}`, args, this.listeners(event));
        }
      });
    }

    data(name, value) {
      this.trigger('phoenix.data', name, value);

      if (value === undefined) {
        const res = $(document).data(name);

        this.trigger('phoenix.data.get', name, res);

        return res;
      }

      $(document).data(name, value);

      this.trigger('phoenix.data.set', name, value);

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

    plugin(name, PluginClass) {
      const self = this;
      $.fn[name] = function (...args) {
        if (!this.data(`phoenix.${name}`)) {
          const instance = new PluginClass(this, ...args);
          this.data(`phoenix.${name}`, instance);
          self.trigger('jquery.plugin.created', { name, ele: this, instance });
        }

        const instance = this.data(`phoenix.${name}`);

        self.trigger('jquery.plugin.get', { name, ele: this, instance });

        return instance;
      };

      return this;
    }
  }

  window.PhoenixCore = PhoenixCore;
})(jQuery);
