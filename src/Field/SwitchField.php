<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Phoenix\PhoenixPackage;
use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Form\Field\CheckboxField;

/**
 * The SwitchField class.
 *
 * @method $this|mixed round(bool $value = null)
 * @method $this|mixed circle(bool $value = null)
 * @method $this|mixed shape(string $value = null)
 * @method $this|mixed color(string $value = null)
 * @method $this|mixed size(string $value = null)
 * @method $this|mixed checkedValue(string $value = null)
 * @method $this|mixed uncheckedValue(string $value = null)
 *
 * @since  1.4
 */
class SwitchField extends CheckboxField
{
    /**
     * Property type.
     *
     * @var  string
     */
    protected $type = 'switch';

    /**
     * Property inited.
     *
     * @var bool
     */
    protected static $inited = false;

    /**
     * prepareRenderInput
     *
     * @param array $attrs
     *
     * @return  array
     */
    public function prepare(&$attrs)
    {
        parent::prepare($attrs);

        $value = $this->getValue();

        if ($this->get('checked_value') !== null) {
            $attrs['checked'] = $value == $this->get('checked_value') ? 'true' : null;
        }

        if ($this->get('round')) {
            $this->set('shape', 'slider-round');
        }

        if ($this->get('circle')) {
            $this->set('shape', 'slider-circle');
        }

        return $attrs;
    }

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  mixed
     */
    public function buildInput($attrs)
    {
        static::initAssets();

        $attrs['value'] = $this->get('checked_value', 1);

        return parent::buildInput($attrs);
    }

    /**
     * initAssets
     *
     * @return  void
     */
    protected static function initAssets()
    {
        if (static::$inited) {
            return;
        }

        BootstrapScript::switcher();

        static::$inited = true;
    }

    /**
     * getAccessors
     *
     * @return  array
     *
     * @since   3.1.2
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'round',
            'circle',
            'shape',
            'color',
            'size',
            'checkedValue' => 'checked_value',
            'uncheckedValue' => 'unchecked_value',
        ]);
    }
}
