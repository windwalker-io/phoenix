<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Form\Filter;

/**
 * The UtcFilter class.
 *
 * @since  1.4
 */
class UtcFilter extends TimezoneFilter
{
    /**
     * TimezoneFilter constructor.
     *
     * @param string $from
     */
    public function __construct($from = null)
    {
        parent::__construct($from, 'UTC');
    }
}
