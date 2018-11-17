<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Form\Filter;

use Windwalker\Core\DateTime\Chronos;
use Windwalker\Form\Filter\FilterInterface;
use Windwalker\Ioc;

/**
 * The TimezoneFilter class.
 *
 * @since  1.4
 */
class TimezoneFilter implements FilterInterface
{
    /**
     * Property from.
     *
     * @var string
     */
    protected $from;

    /**
     * Property to.
     *
     * @var  string
     */
    protected $to;

    /**
     * Property format.
     *
     * @var string
     */
    protected $format;

    /**
     * TimezoneFilter constructor.
     *
     * @param string $from
     * @param string $to
     * @param string $format
     */
    public function __construct($from = null, $to = 'UTC', $format = null)
    {
        $this->from = $from ?: Ioc::getConfig()->get('system.timezone', 'UTC');
        $this->to   = $to;
        $this->format = $format ?: Chronos::getSqlFormat();
    }

    /**
     * clean
     *
     * @param string $text
     *
     * @return  mixed
     */
    public function clean($text)
    {
        if (!$text) {
            return Chronos::getNullDate();
        }

        if ($this->from === $this->to) {
            return Chronos::toFormat($text, $this->format);
        }

        return Chronos::convert($text, $this->from, $this->to, $this->format);
    }
}
