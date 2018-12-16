"use strict";

/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */
(function () {
  var PhoenixSwitch = window.PhoenixSwitch = {
    name: 'phoenix-switch',
    template: "\n<label class=\"phoenix-switch\" :for=\"idName\" :class=\"[size ? 'switch-' + size : '']\">\n    <input :id=\"idName + '-unchecked'\" :name=\"name\" type=\"hidden\"\n        :value=\"falseValue\"\n        :disabled=\"disabled\"\n    />\n    <input type=\"checkbox\" :name=\"name\" :id=\"idName\" class=\"form-control\" :class=\"classes\"\n        :true-value=\"trueValue\" :false-value=\"falseValue\" :disabled=\"disabled\"\n        :value=\"trueValue\"\n        :checked=\"currentValue == trueValue\"\n        @change=\"changed\"\n        @click=\"click\">\n    <span\n        class=\"switch-slider\"\n        :class=\"['slider-' + shape, color ? 'btn-' + color : 'btn-default']\"\n    ></span>\n</label>\n    ",
    data: function data() {
      return {
        idName: '',
        currentValue: null
      };
    },
    props: {
      id: String,
      classes: String,
      value: null,
      name: String,
      disabled: Boolean,
      trueValue: {
        default: '1'
      },
      falseValue: {
        default: '0'
      },
      size: {
        type: String,
        default: 'default'
      },
      color: {
        type: String,
        default: 'primary'
      },
      shape: {
        type: String,
        default: 'square'
      }
    },
    created: function created() {
      if (this.name == null && this.id === null) {
        throw new Error('[Phoenix Switch] You must provide "name" or "id" attribute.');
      }

      this.idName = this.id || 'input-' + this.getDashedName();
      this.currentValue = this.value;
    },
    methods: {
      getDashedName: function getDashedName() {
        return this.name.replace(/\[/g, '-').replace(/]/, '');
      },
      changed: function changed($event) {
        this.currentValue = $event.srcElement.checked ? this.trueValue : this.falseValue;
      },
      click: function click($event) {
        this.$emit('click', $event);
      }
    },
    watch: {
      currentValue: function currentValue() {
        this.$emit('input', this.currentValue);
        this.$emit('change', this.currentValue);
      },
      value: function value() {
        this.currentValue = this.value;
      }
    }
  };

  if (typeof window.Vue !== 'undefined') {
    window.Vue.component('phoenix-switch', PhoenixSwitch);
  }
})();
//# sourceMappingURL=switch.js.map
