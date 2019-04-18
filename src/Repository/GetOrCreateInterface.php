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
 * @since  1.8.4
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
     * @since  1.8.4
     */
    public function getItemOrCreate($conditions, $data = []): Data;
}
