<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;

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
	 * init
	 *
	 * @return  void
	 */
	public function boot()
	{
		parent::boot();

		Translator::loadFile('phoenix', 'ini', $this);
	}
}
