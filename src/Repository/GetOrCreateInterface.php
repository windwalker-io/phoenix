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
use Windwalker\Record\Record;

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
     * @return  Record
     *
     * @since  1.8.4
     */
    public function getItemOrCreate($conditions, $data = []): Record;

    /**
     * updateOrCreate
     *
     * @param mixed         $data
     * @param array         $initData
     * @param array|mixed   $condFields
     *
     * @return  Record
     *
     * @throws \Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    public function updateItemOrCreate(
        $data,
        array $initData = [],
        $condFields = null
    ): Record;
}
