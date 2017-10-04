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
 * @since  __DEPLOY_VERSION__
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
	 * TimezoneFilter constructor.
	 *
	 * @param string $from
	 * @param string $to
	 */
	public function __construct($from = null, $to = 'UTC')
	{
		$this->from = $from ? : Ioc::getConfig()->get('system.timezone', 'UTC');
		$this->to   = $to;
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
		if (!$text)
		{
			return $text;
		}

		if ($this->from === $this->to)
		{
			return $text;
		}

		return Chronos::convert($text, $this->from, $this->to);
	}
}
