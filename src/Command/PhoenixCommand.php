<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command;

use Phoenix\Command\Phoenix\AssetCommand;
use Phoenix\Command\Phoenix\FormCommand;
use Phoenix\Command\Phoenix\RecordCommand;
use Windwalker\Console\Command\Command;

/**
 * The PhoenixCommand class.
 *
 * @since  1.0
 */
class PhoenixCommand extends Command
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'phoenix';

    /**
     * Property description.
     *
     * @var  string
     */
    protected $description = 'The Phoenix RAD package.';

    /**
     * init
     *
     * @return  void
     */
    protected function init()
    {
        $this->addCommand(new AssetCommand());
        $this->addCommand(new FormCommand());
        $this->addCommand(new RecordCommand());
    }
}
