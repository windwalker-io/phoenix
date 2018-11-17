<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Action\Package\Subsystem;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\FileOperator\CopyOperator;

/**
 * The PrepareAction class.
 *
 * @since  1.0
 */
class PrepareAction extends AbstractAction
{
    /**
     * Do this execute.
     *
     * @return  mixed
     */
    protected function doExecute()
    {
        /** @var CopyOperator $copyOperator */
        $copyOperator = $this->container->get('operator.factory')->getOperator('copy');

        $src  = $this->config['dir.src'];
        $dest = $this->config['dir.dest'];

        $files = [
            'Resources/language',
        ];

        foreach ($files as $file) {
            if (!file_exists($src . '/' . $file)) {
                continue;
            }

            $copyOperator->copy(
                $src . '/' . $file,
                $dest . '/' . $file,
                $this->config['replace']
            );
        }
    }
}
