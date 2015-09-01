<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Form\Renderer;

use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Form\Form;

/**
 * The BootstrapRenderer class.
 *
 * @since  {DEPLOY_VERSION}
 */
class BootstrapRenderer
{
	/**
	 * Property renderers.
	 *
	 * @var  callable[]
	 */
	protected static $renderers = array();

	/**
	 * render
	 *
	 * @param AbstractField $field
	 * @param Form          $form
	 *
	 * @return  string
	 */
	public function render(AbstractField $field, Form $form)
	{
		$type = $field->getType();

		$handler = static::getRenderer($type);

		if ($handler)
		{
			return call_user_func($handler, $field, $form);
		}

		if (is_callable(array(__CLASS__, 'render' . ucfirst($type))))
		{
			return call_user_func(array(__CLASS__, 'render' . ucfirst($type)), $field, $form);
		}

		return static::renderDefault($field, $form);
	}

	/**
	 * renderRadio
	 *
	 * @param AbstractField $field
	 * @param Form          $form
	 *
	 * @return  string
	 */
	public static function renderRadio(AbstractField $field, Form $form)
	{
		return WidgetHelper::render('phoenix.bootstrap.field.radio', array(
			'form' => $form,
			'field' => $field
		), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * renderRadio
	 *
	 * @param AbstractField $field
	 * @param Form          $form
	 *
	 * @return  string
	 */
	public static function renderCheckboxes(AbstractField $field, Form $form)
	{
		return WidgetHelper::render('phoenix.bootstrap.field.checkboxes', array(
			'form' => $form,
			'field' => $field
		), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * renderHidden
	 *
	 * @param AbstractField $field
	 *
	 * @return  string
	 */
	protected function renderHidden(AbstractField $field)
	{
		return $field->renderInput();
	}

	/**
	 * renderDefault
	 *
	 * @param   AbstractField $field
	 * @param   Form          $form
	 *
	 * @return  string
	 */
	protected static function renderDefault(AbstractField $field, Form $form)
	{
		return WidgetHelper::render('phoenix.bootstrap.field.default', array(
			'form' => $form,
			'field' => $field
		), WidgetHelper::ENGINE_BLADE);
	}

	public static function addRenderer($type, $renderer)
	{
		if (!is_callable($renderer))
		{
			throw new \InvalidArgumentException($type . ' renderer should be callable.');
		}

		static::$renderers[$type] = $renderer;
	}

	/**
	 * getRenderer
	 *
	 * @param   string  $name
	 *
	 * @return  \callable
	 */
	public static function getRenderer($name)
	{
		if (!isset(static::$renderers[$name]))
		{
			return null;
		}

		return static::$renderers[$name];
	}

	/**
	 * Method to get property Renderers
	 *
	 * @return  \callable[]
	 */
	public static function getRenderers()
	{
		return static::$renderers;
	}

	/**
	 * Method to set property renderers
	 *
	 * @param   \callable[] $renderers
	 *
	 * @return  void
	 */
	public static function setRenderers($renderers)
	{
		static::$renderers = $renderers;
	}
}
