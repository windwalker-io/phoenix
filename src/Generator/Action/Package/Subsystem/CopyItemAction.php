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
use Windwalker\String\StringHelper;

/**
 * The CopyItemAction class.
 *
 * @since  1.0
 */
class CopyItemAction extends AbstractAction
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
        $item = StringHelper::quote('controller.item.name.cap', $this->config['tagVariables']);

        $files = [
            'Controller/%s',
            'Field',
            'Form/%s',
            'Model/%sModel.php.tpl',
            'View/%s',
            'Templates/' . StringHelper::quote('controller.item.name.lower', $this->config['tagVariables']),
        ];

        foreach ($files as $file) {
            $file = sprintf($file, $item);

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
