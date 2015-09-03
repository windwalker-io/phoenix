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
	 * Property aliases.
	 *
	 * @var  array
	 */
	protected static $aliases = array(
		'spacer' => 'default'
	);

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

		$type = static::resolveAlias($type);

		$handler = static::getRenderer($type);

		if ($handler)
		{
			return call_user_func($handler, $field, $form);
		}

		if (is_callable(array(__CLASS__, 'render' . ucfirst($type))))
		{
			return call_user_func(array(__CLASS__, 'render' . ucfirst($type)), $field, $form);
		}

		return static::renderInput($field, $form);
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
	 * renderSpacer
	 *
	 * @param AbstractField $field
	 * @param Form          $form
	 *
	 * @return  string
	 */
	public static function renderSpacer(AbstractField $field, Form $form)
	{
		return WidgetHelper::render('phoenix.bootstrap.field.spacer', array(
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
	protected static function renderHidden(AbstractField $field)
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
	protected static function renderInput(AbstractField $field, Form $form)
	{
		return WidgetHelper::render('phoenix.bootstrap.field.input', array(
			'form' => $form,
			'field' => $field
		), WidgetHelper::ENGINE_BLADE);
	}

	/**
	 * renderDefault
	 *
	 * @param   AbstractField $field
	 *
	 * @return  string
	 */
	protected static function renderDefault(AbstractField $field)
	{
		return $field->render();
	}

	/**
	 * resolveAlias
	 *
	 * @param   string  $type
	 *
	 * @return  string
	 */
	public static function resolveAlias($type)
	{
		if (isset(static::$aliases[$type]))
		{
			return static::$aliases[$type];
		}

		return $type;
	}

	/**
	 * addAlias
	 *
	 * @param   string  $type
	 * @param   string  $alias
	 *
	 * @return  void
	 */
	public static function addAlias($type, $alias)
	{
		static::$aliases[$type] = $alias;
	}

	/**
	 * Method to get property Aliases
	 *
	 * @return  array
	 */
	public static function getAliases()
	{
		return static::$aliases;
	}

	/**
	 * Method to set property aliases
	 *
	 * @param   array $aliases
	 *
	 * @return  void
	 */
	public static function setAliases($aliases)
	{
		static::$aliases = $aliases;
	}

	/**
	 * addRenderer
	 *
	 * @param   string    $type
	 * @param   callable  $renderer
	 *
	 * @return  void
	 */
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
