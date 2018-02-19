<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Model;

use Windwalker\Core\Pagination\Pagination;
use Windwalker\Data\DataSet;

/**
 * Interface ListRepositoryInterface
 *
 * @since  1.1
 */
interface ListRepositoryInterface
{
    /**
     * getItems
     *
     * @return  DataSet
     * @throws \RuntimeException
     */
    public function getItems();

    /**
     * getPagination
     *
     * @param integer $total
     *
     * @return Pagination
     */
    public function getPagination($total = null);

    /**
     * getPagination
     *
     * @return Pagination
     */
    public function getSimplePagination();

    /**
     * getTotal
     *
     * @return  integer
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getTotal();

    /**
     * getStart
     *
     * @return  integer
     */
    public function getStart();

    /**
     * setLimit
     *
     * @param   integer $limit
     *
     * @return  static
     */
    public function limit($limit);

    /**
     * getLimit
     *
     * @return  integer
     */
    public function getLimit();

    /**
     * setLimit
     *
     * @param   integer $page
     *
     * @return  static
     */
    public function page($page);

    /**
     * getLimit
     *
     * @return  integer
     */
    public function getPage();

    /**
     * Method to set property fuzzySearching
     *
     * @param   bool $bool
     *
     * @return  bool|static  Return self to support chaining.
     */
    public function fuzzySearching($bool = null);

    /**
     * addFilter
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  static
     */
    public function addFilter($key, $value);

    /**
     * addSearch
     *
     * @param   string $key
     * @param   mixed  $value
     *
     * @return  static
     */
    public function addSearch($key, $value);

    /**
     * select
     *
     * @param   string|array $fields
     *
     * @return  static
     */
    public function select($fields);

    /**
     * setOrdering
     *
     * @param  string     $order
     * @param  bool|false $direction
     *
     * @return  static
     */
    public function ordering($order, $direction = false);

    /**
     * appendWhere
     *
     * @param   string|array $conditions
     * @param   array        ...$args
     *
     * @return static
     */
    public function where($conditions, ...$args);

    /**
     * Or Where.
     *
     * @param   mixed|callable $conditions A string, array of where conditions or callback to support logic.
     *
     * @return static
     */
    public function orWhere($conditions);

    /**
     * Method to add a variable to an internal array that will be bound to a prepared SQL statement before query
     * execution. Also removes a variable that has been bounded from the internal bounded array when the passed in
     * value is null.
     *
     * @param   string|integer|array $key            The key that will be used in your SQL query to reference the
     *                                               value. Usually of the form ':key', but can also be an integer.
     * @param   mixed                &$value         The value that will be bound. The value is passed by reference to
     *                                               support output parameters such as those possible with stored
     *                                               procedures.
     * @param   integer              $dataType       Constant corresponding to a SQL datatype.
     * @param   integer              $length         The length of the variable. Usually required for OUTPUT
     *                                               parameters.
     * @param   array                $driverOptions  Optional driver options to be used.
     *
     * @return  static
     *
     * @since   3.0
     */
    public function bind($key = null, $value = null, $dataType = \PDO::PARAM_STR, $length = 0, $driverOptions = []);

    /**
     * appendHaving
     *
     * @param string|array $conditions
     * @param array        ...$args
     *
     * @return static
     */
    public function having($conditions, ...$args);

    /**
     * appendWhereOr
     *
     * @param   mixed|callable $conditions
     *
     * @return  static
     */
    public function orHaving($conditions);
}
