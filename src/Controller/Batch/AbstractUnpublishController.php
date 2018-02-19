<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

/**
 * The UnpublishController class.
 *
 * @since  1.0.5
 */
abstract class AbstractUnpublishController extends AbstractBatchController
{
    /**
     * Property action.
     *
     * @var  string
     */
    protected $action = 'unpublish';

    /**
     * Property data.
     *
     * @var  array
     */
    protected $data = [
        'state' => 0,
    ];
}
