<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Phoenix\Repository;

use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;

/**
 * Interface GetOrCreateInterface
 *
 * @since  __DEPLOY_VERSION__
 */
interface GetOrCreateInterface
{
    /**
     * getItemOrCreate
     *
     * @param mixed               $conditions
     * @param array|DataInterface $data
     *
     * @return  Data
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getItemOrCreate($conditions, $data = []): Data;
}
