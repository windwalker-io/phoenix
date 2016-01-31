<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Form;

/**
 * The FieldDefinitionGenerator class.
 * 
 * @since  1.0
 */
class FieldDefinitionGenerator
{
	/**
	 * generate
	 *
	 * @param string     $type
	 * @param string     $name
	 * @param \stdClass  $column
	 *
	 * @return  string
	 */
	public static function generate($type, $name, $column)
	{
		$className = get_called_class();

		$args = [$type, $name, ucfirst($name), $column];

		$method = 'gen' . ucfirst($type) . ucfirst($name);

		if (!is_callable([__CLASS__, $method]))
		{
			$method = 'gen' . ucfirst($name);
		}

		if (!is_callable([__CLASS__, $method]))
		{
			$method = 'gen' . ucfirst($type);
		}

		if (!is_callable([__CLASS__, $method]))
		{
			$method = 'genVarchar';
		}

		return call_user_func_array([$className, $method], $args) . "\n";
	}

	/**
	 * genVarchar
	 *
	 * @param string     $type
	 * @param string     $name
	 * @param string     $label
	 * @param \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genVarchar($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Field\TextField)
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('default', null);
HTML;
	}

	/**
	 * genTinyint
	 *
	 * @param string     $type
	 * @param string     $name
	 * @param string     $label
	 * @param \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genTinyint($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Field\RadioField)
	->label('$label')
	->addOption(new Option('Yes', 1))
	->addOption(new Option('No', 0))
	->set('class', '')
	->set('labelClass', '')
	->set('default', 1);
HTML;
	}

	/**
	 * genTinyint
	 *
	 * @param string     $type
	 * @param string     $name
	 * @param string     $label
	 * @param \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genChar($type, $name, $label, $column)
	{
		$comment = $column->Comment;

		$options = array_map(function($value)
		{
			$value = trim($value);
			$text = ucfirst($value);

			return "->addOption(new Option('$text', '$value'))";
		}, explode(',', $comment));

		$options = implode("\n\t", $options);

		return <<<HTML
// $label
\$form->add('$name', new Field\ListField)
	->label('$label')
	$options
	->set('class', '')
	->set('labelClass', '')
	->set('default', 1);
HTML;
	}

	/**
	 * genText
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genText($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Field\TextareaField)
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('rows', 7)
	->set('default', null);
HTML;
	}

	/**
	 * genLongtext
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genLongtext($type, $name, $label, $column)
	{
		return static::genText($type, $name, $label, $column);
	}

	/**
	 * genMediumtext
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genMediumtext($type, $name, $label, $column)
	{
		return static::genText($type, $name, $label, $column);
	}

	/**
	 * genID
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genID($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Field\TextField)
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('readonly', true);
HTML;
	}

	/**
	 * genPassword
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genPassword($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Field\PasswordField)
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('autocomplete', 'off');

// Confirm Password
\$form->add('password2', new Field\PasswordField)
	->label('Confirm Password')
	->set('class', '')
	->set('labelClass', '')
	->set('autocomplete', 'off');
HTML;
	}

	/**
	 * genDatetime
	 *
	 * @param  string     $type
	 * @param  string     $name
	 * @param  string     $label
	 * @param  \stdClass  $column
	 *
	 * @return  string
	 */
	public static function genDatetime($type, $name, $label, $column)
	{
		return <<<HTML
// $label
\$form->add('$name', new Phoenix\Field\CalendarField)
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('default', null);
HTML;
	}
}
