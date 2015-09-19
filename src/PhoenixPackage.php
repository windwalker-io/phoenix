<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix;

use Phoenix\Provider\PhoenixProvider;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\DI\Container;

define('PHOENIX_ROOT', dirname(__DIR__));
define('PHOENIX_SOURCE', PHOENIX_ROOT . '/src');
define('PHOENIX_TEMPLATES', PHOENIX_ROOT . '/templates');

/**
 * The SimpleRADPackage class.
 * 
 * @since  1.0
 */
class PhoenixPackage extends AbstractPackage
{
	/**
	 * initialise
	 *
	 * @return  void
	 */
	public function initialise()
	{
		parent::initialise();

		Translator::loadFile('phoenix', 'ini', $this);
	}

	/**
	 * registerProviders
	 *
	 * @param Container $container
	 *
	 * @return  void
	 */
	public function registerProviders(Container $container)
	{
		$container->registerServiceProvider(new PhoenixProvider);
	}
}
