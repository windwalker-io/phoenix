<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Phoenix\PhoenixPackage;
use Windwalker\Core\Asset\AbstractScript;
use Windwalker\Core\Package\PackageHelper;

/**
 * The ScriptManager class.
 *
 * @since  1.0.13
 */
abstract class AbstractPhoenixScript extends AbstractScript
{
    /**
     * phoenixName
     *
     * @return  string
     */
    protected static function phoenixName()
    {
        static $name;

        if ($name) {
            return $name;
        }

        return $name = PackageHelper::getAlias(PhoenixPackage::class);
    }
}
