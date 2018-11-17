<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

if (!function_exists('bignum')) {
    /**
     * bc
     *
     * @param mixed $value
     *
     * @return  \Brick\Math\BigDecimal
     *
     * @since  __DEPLOY_VERSION__
     */
    function bignum($value)
    {
        if (!class_exists(\Brick\Math\BigDecimal::class)) {
            throw new DomainException('Please install brick/math first.');
        }

        return \Brick\Math\BigDecimal::of($value);
    }
}
