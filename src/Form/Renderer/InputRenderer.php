<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Form\Renderer;

use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Dom\HtmlElement;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Form\Field\HiddenField;
use Windwalker\Form\Renderer\FormRendererInterface;

/**
 * The BootstrapRenderer class.
 *
 * @since  1.0.13
 */
class InputRenderer implements FormRendererInterface
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
	 * Property templatePrefix.
	 *
	 * @var  string
	 */
	protected static $templatePrefix = 'phoenix.bootstrap.';

	/**
	 * renderField
	 *
	 * @param AbstractField $field
	 * @param array         $attribs
	 *
	 * @return string
	 */
	public function renderField(AbstractField $field, array $attribs = array())
	{
		return WidgetHelper::render('phoenix.bootstrap.field.control', [
			'field'     => $field,
			'labelHtml' => $field->renderLabel(),
			'inputHtml' => $field->renderInput(),
			'attribs'   => $attribs
		], WidgetHelper::EDGE);
	}

	/**
	 * renderLabel
	 *
	 * @param AbstractField $field
	 * @param array         $attribs
	 *
	 * @return string
	 */
	public function renderLabel(AbstractField $field, array $attribs = array())
	{
		if ($field instanceof HiddenField)
		{
			return '';
		}

		$attribs['class'] .= ' hasTooltip ' . $field->getAttribute('labelWidth', 'col-md-3');

		$label = $field->getLabel();

		if ($field->get('required'))
		{
			$label = '<span class="windwalker-input-required-hint">*</span> ' . $label;
		}

		return (string) new HtmlElement('label', $label, $attribs);
	}

	/**
	 * renderDefault
	 *
	 * @param   AbstractField $field
	 * @param   array         $attribs
	 *
	 * @return string
	 */
	public function renderInput(AbstractField $field, array $attribs = [])
	{
		$type = $field->getType();

		$type = static::resolveAlias($type);

		$handler = static::getRenderer($type);

		if ($handler)
		{
			return $handler($field);
		}

		$method = 'render' . ucfirst($type);

		if (is_callable(array($this, $method)))
		{
			return $this->$method($field, $attribs);
		}

		$attribs['class'] .= ' form-control';

		return $field->buildInput($attribs);
	}

	/**
	 * renderRadio
	 *
	 * @param AbstractField $field
	 *
	 * @return  string
	 */
	public static function renderRadio(AbstractField $field, array $attribs = [])
	{
		$attribs['class'] .= ' radio-container input-list-container';

		return WidgetHelper::render(static::getTemplatePrefix() . 'field.radio', array(
			'attribs' => $attribs,
			'field' => $field
		), WidgetHelper::EDGE);
	}

	/**
	 * renderRadio
	 *
	 * @param AbstractField $field
	 * @param array         $attribs
	 *
	 * @return string
	 */
	public static function renderCheckboxes(AbstractField $field, array $attribs = [])
	{
		$attribs['class'] .= ' checkbox-container input-list-container';

		return WidgetHelper::render(static::getTemplatePrefix() . 'field.checkboxes', array(
			'attribs' => $attribs,
			'field' => $field
		), WidgetHelper::EDGE);
	}

	/**
	 * renderSpacer
	 *
	 * @param AbstractField $field
	 * @param array         $attribs
	 *
	 * @return string
	 */
	public static function renderSpacer(AbstractField $field, array $attribs = [])
	{
		return WidgetHelper::render(static::getTemplatePrefix() . 'field.spacer', array(
			'attribs' => $attribs,
			'field' => $field
		), WidgetHelper::EDGE);
	}

	/**
	 * renderHidden
	 *
	 * @param AbstractField $field
	 * @param array         $attribs
	 *
	 * @return string
	 */
	protected static function renderHidden(AbstractField $field, array $attribs = [])
	{
		return $field->buildInput($attribs);
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
	public static function setRenderers(array $renderers)
	{
		static::$renderers = $renderers;
	}

	/**
	 * Method to get property TemplatePrefix
	 *
	 * @return  string
	 */
	public static function getTemplatePrefix()
	{
		return static::$templatePrefix;
	}

	/**
	 * Method to set property templatePrefix
	 *
	 * @param   string $templatePrefix
	 *
	 * @return  void
	 */
	public static function setTemplatePrefix($templatePrefix)
	{
		static::$templatePrefix = $templatePrefix;
	}
}
