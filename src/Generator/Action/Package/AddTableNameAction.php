<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\GeneratorHelper;

/**
 * The AddTableNameAction class.
 *
 * @since  1.0
 */
class AddTableNameAction extends AbstractAction
{
    /**
     * Do this execute.
     *
     * @return  mixed
     */
    protected function doExecute()
    {
        $constant = $this->config['replace.controller.list.name.upper'];
        $table    = $this->config['replace.controller.list.name.lower'];

        $file = $this->config['dir.dest'] . '/Table/Table.php';

        if (!is_file($file)) {
            return;
        }

        $code = file_get_contents($file);

        if (strpos($code, 'const ' . $constant . ' = ') !== false) {
            return;
        }

        $replace = "\tconst $constant = '$table';\n\n";

        $code = GeneratorHelper::addBeforePlaceholder('db-table', $code, $replace);

        file_put_contents($file, $code);

        $this->io->out('[<info>Action</info>] Add table name: ' . $table . ' to Table class.');
    }
}
