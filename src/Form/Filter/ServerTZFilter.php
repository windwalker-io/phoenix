<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Form\Filter;

use Windwalker\Core\DateTime\Chronos;

/**
 * The ServerTZFilter class.
 *
 * @since  __DEPLOY_VERSION__
 */
class ServerTZFilter extends TimezoneFilter
{
    /**
     * TimezoneFilter constructor.
     *
     * @param string $from
     */
    public function __construct($from = null)
    {
        $to = Chronos::getServerDefaultTimezone();

        parent::__construct($from, $to);
    }
}
