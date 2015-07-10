<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Form;

/**
 * The FieldDefinitionGenerator class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class FieldDefinitionGenerator
{
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
\$form->addField(new Field\TextField('$name', '$label'))
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
\$form->addField(new Field\RadioField('$name', '$label'))
	->addOption(new Option('Yes', 1))
	->addOption(new Option('No', 0))
	->set('class', '')
	->set('labelClass', '')
	->set('default', 1);
HTML;
	}

	public static function genText($type, $name, $label, $column)
	{
		return <<<HTML
\$form->addField(new Field\TextareaField('$name', '$label'))
	->set('class', '')
	->set('labelClass', '')
	->set('rows', 7)
	->set('default', null);
HTML;
	}

	public static function genID($type, $name, $label, $column)
	{
		return <<<HTML
\$form->addField(new Field\TextField('$name', '$label'))
	->set('class', '')
	->set('labelClass', '')
	->set('readonly', true);
HTML;
	}

	public static function genPassword($type, $name, $label, $column)
	{
		return <<<HTML
\$form->addField(new Field\PasswordField('password', 'Password'))
	->set('class', '')
	->set('labelClass', '')
	->set('autocomplete', 'off');

\$form->addField(new Field\PasswordField('password2', 'Password Again'))
	->set('class', '')
	->set('labelClass', '')
	->set('autocomplete', 'off');
HTML;
	}
}
