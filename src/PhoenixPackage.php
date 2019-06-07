<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix;

use Phoenix\Listener\JsCommandListener;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Security\CsrfProtection;

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
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function boot()
    {
        parent::boot();

        Translator::loadFile('phoenix', 'ini', $this);

        if ($this->app->isWeb()) {
            CsrfProtection::setMessage(__('phoenix.message.invalid.token'));

            $this->getDispatcher()->addListener(new JsCommandListener());
        }
    }
}
