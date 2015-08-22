<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action;

use Windwalker\DI\Container;
use Windwalker\Ioc;

/**
 * The AbstractAction class.
 * 
 * @since  {DEPLOY_VERSION}
 */
abstract class AbstractAction extends \Muse\Action\AbstractAction
{
	/**
	 * Property container.
	 *
	 * @var  Container
	 */
	protected $container;

	/**
	 * Constructor.
	 *
	 * @param Container $container
	 */
	public function __construct(Container $container = null)
	{
		$this->container = $container ? : Ioc::factory();
	}
}
