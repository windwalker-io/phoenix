<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Controller\Traits;

use Windwalker\Filter\InputFilter;

/**
 * The FilterWithoutStateTrait class.
 *
 * @since  1.8.3
 */
trait FilterWithoutStateTrait
{
    /**
     * Gets the value from session and input and sets it back to session
     *
     * @param string $name
     * @param string $inputName
     * @param mixed  $default
     * @param string $filter
     * @param string $namespace
     *
     * @return  mixed
     */
    public function getUserStateFromInput(
        $name,
        $inputName,
        $default = null,
        $filter = InputFilter::STRING,
        $namespace = 'default'
    ) {
        return $this->input->get($inputName, $default, $filter);
    }
}
