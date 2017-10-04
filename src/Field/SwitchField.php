<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Phoenix\PhoenixPackage;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Form\Field\CheckboxField;

/**
 * The SwitchField class.
 *
 * @method $this|mixed round(bool $value = null)
 * @method $this|mixed color(string $value = null)
 * @method $this|mixed checkedValue(string $value = null)
 * @method $this|mixed uncheckedValue(string $value = null)
 *
 * @since  __DEPLOY_VERSION__
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

		$attrs['checked'] = $value == $this->get('checked_value') ? 'true' : null;
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
	 * filter
	 *
	 * @return  static
	 */
	public function filter()
	{
		return parent::filter();
	}

	/**
	 * initAssets
	 *
	 * @return  void
	 */
	protected static function initAssets()
	{
		if (static::$inited)
		{
			return;
		}

		$alias = PackageHelper::getAlias(PhoenixPackage::class);

		Asset::addCSS($alias . '/css/bootstrap/switch.min.css');

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
			'color',
			'checkedValue' => 'checked_value',
			'uncheckedValue' => 'unchecked_value',
		]);
	}
}
