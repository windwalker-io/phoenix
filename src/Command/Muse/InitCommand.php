<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Command\Muse;

use Phoenix\Generator\Controller\GeneratorController;
use Windwalker\Console\Command\Command;

/**
 * The InitCommand class.
 *
 * @since  1.0
 */
class InitCommand extends Command
{
    /**
     * An enabled flag.
     *
     * @var bool
     */
    public static $isEnabled = true;

    /**
     * Console(Argument) name.
     *
     * @var  string
     */
    protected $name = 'init';

    /**
     * The command description.
     *
     * @var  string
     */
    protected $description = 'Init a new package.';

    /**
     * The usage to tell user how to use this command.
     *
     * @var string
     */
    protected $usage = 'init <cmd><package_name></cmd> <option>[option]</option>';

    /**
     * init
     *
     * @return  void
     */
    protected function init()
    {
        $this->addGlobalOption('table')
            ->description('The database table name.');

        $this->addGlobalOption('m')
            ->alias('migrate')
            ->description('Run migration.');

        $this->addGlobalOption('s')
            ->alias('seed')
            ->description('Run seeder.');

        $this->addGlobalOption('controller')
            ->description('Only Controller.');

        $this->addGlobalOption('model')
            ->description('Only Model.');

        $this->addGlobalOption('view')
            ->description('Only View.');
    }

    /**
     * Execute this command.
     *
     * @return int|void
     */
    protected function doExecute()
    {
        $generator = new GeneratorController($this);

        $generator->setTask('package.init')->execute();
    }
}
