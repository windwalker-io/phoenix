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

		$args = [$type, $name, str_replace('_', ' ', ucfirst($name)), $column];

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

		return $className::$method($type, $name, str_replace('_', ' ', ucfirst($name)), $column) . "\n";
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
\$this->text('$name')
	->label('$label')
	->description('{$column->Comment}')
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
\$this->radio('$name')
	->label('$label')
	->description('{$column->Comment}')
	->option('Yes', 1)
	->option('No', 0)
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

			return "->option('$text', '$value')";
		}, explode(',', $comment));

		$options = implode("\n\t", $options);

		return <<<HTML
// $label
\$this->list('$name')
	->label('$label')
	->description('{$column->Comment}')
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
\$this->textarea('$name')
	->label('$label')
	->description('{$column->Comment}')
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
\$this->hidden('$name');
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
\$this->password('$name')
	->description('Password')
	->label('$label')
	->set('class', '')
	->set('labelClass', '')
	->set('autocomplete', 'off');

// Confirm Password
\$this->password('password2')
	->label('Confirm Password')
	->description('Confirm Password')
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
\$this->calendar('$name')
	->label('$label')
	->description('{$column->Comment}')
	->set('class', '')
	->set('labelClass', '')
	->set('default', null);
HTML;
	}
}
