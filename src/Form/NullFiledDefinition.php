<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Form;

use Windwalker\Form\FieldDefinitionInterface;
use Windwalker\Form\Form;

/**
 * The NullFiledDefinition class.
 *
 * @since  1.0
 */
class NullFiledDefinition implements FieldDefinitionInterface
{
    /**
     * Define the form fields.
     *
     * @param Form $form The Windwalker form object.
     *
     * @return  void
     */
    public function define(Form $form)
    {

    }
}
