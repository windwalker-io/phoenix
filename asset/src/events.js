/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

(() => {
  'use strict';

  window.PhoenixEventMixin = Mixin((superclass) => class extends superclass {
    _listeners = {};

    on(event, handler) {
      if (Array.isArray(event)) {
        event.forEach(e => this.on(e, handler));
        return this;
      }

      if (this._listeners[event] === undefined) {
        this._listeners[event] = [];
      }

      this._listeners[event].push(handler);

      return this;
    }

    once(event, handler) {
      if (Array.isArray(event)) {
        event.forEach(e => this.once(e, handler));
        return this;
      }

      handler._once = true;

      this.on(event, handler);
    }

    off(event, callback = null) {
      if (callback !== null) {
        this._listeners[event] = this.listeners(event).filter((listener) => listener !== callback);
        return this;
      }

      delete this._listeners[event];

      return this;
    }

    trigger(event, ...args) {
      if (Array.isArray(event)) {
        event.forEach(e => this.trigger(e));
        return this;
      }

      this.listeners(event).forEach(listener => {
        listener(...args);
      });

      // Remove once
      this._listeners[event] = this.listeners(event).filter((listener) => listener._once !== true);

      return this;
    }

    listeners(event) {
      if (typeof event !== 'string') {
        throw new Error(`get listeners event name should only use string.`);
      }

      return this._listeners[event] === undefined ? [] : this._listeners[event];
    }
  });

  window.PhoenixEvent = class PhoenixEvent extends PhoenixEventMixin(class {}) {};
})();
