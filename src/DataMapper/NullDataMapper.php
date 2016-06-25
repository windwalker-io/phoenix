<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\DataMapper;

use Windwalker\Data\Data;
use Windwalker\Data\DataSet;
use Windwalker\DataMapper\Adapter\DatabaseAdapterInterface;
use Windwalker\DataMapper\DataMapper;

/**
 * The NullDataMapper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class NullDataMapper extends DataMapper
{
	/**
	 * NullDataMapper constructor.
	 *
	 * @param string                   $table
	 * @param array|string             $keys
	 * @param DatabaseAdapterInterface $db
	 */
	public function __construct()
	{
	}

	/**
	 * Find records and return data set.
	 *
	 * Example:
	 * - `$mapper->find(array('id' => 5), 'date', 20, 10);`
	 * - `$mapper->find(null, 'id', 0, 1);`
	 *
	 * @param   mixed    $conditions Where conditions, you can use array or Compare object.
	 *                               Example:
	 *                               - `array('id' => 5)` => id = 5
	 *                               - `new GteCompare('id', 20)` => 'id >= 20'
	 *                               - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 * @param   mixed    $order      Order sort, can ba string, array or object.
	 *                               Example:
	 *                               - `id ASC` => ORDER BY id ASC
	 *                               - `array('catid DESC', 'id')` => ORDER BY catid DESC, id
	 * @param   integer  $start      Limit start number.
	 * @param   integer  $limit      Limit rows.
	 *
	 * @return  mixed|DataSet Found rows data set.
	 * @since   2.0
	 */
	public function find($conditions = array(), $order = null, $start = null, $limit = null)
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Find records without where conditions and return data set.
	 *
	 * Same as `$mapper->find(null, 'id', $start, $limit);`
	 *
	 * @param mixed   $order Order sort, can ba string, array or object.
	 *                       Example:
	 *                       - 'id ASC' => ORDER BY id ASC
	 *                       - array('catid DESC', 'id') => ORDER BY catid DESC, id
	 * @param integer $start Limit start number.
	 * @param integer $limit Limit rows.
	 *
	 * @return mixed|DataSet Found rows data set.
	 */
	public function findAll($order = null, $start = null, $limit = null)
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Find one record and return a data.
	 *
	 * Same as `$mapper->find($conditions, 'id', 0, 1);`
	 *
	 * @param mixed $conditions Where conditions, you can use array or Compare object.
	 *                          Example:
	 *                          - `array('id' => 5)` => id = 5
	 *                          - `new GteCompare('id', 20)` => 'id >= 20'
	 *                          - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 * @param mixed $order      Order sort, can ba string, array or object.
	 *                          Example:
	 *                          - `id ASC` => ORDER BY id ASC
	 *                          - `array('catid DESC', 'id')` => ORDER BY catid DESC, id
	 *
	 * @return mixed|Data Found row data.
	 */
	public function findOne($conditions = array(), $order = null)
	{
		$class = $this->getDataClass();

		return new $class;
	}

	/**
	 * Find column as an array.
	 *
	 * @param string  $column     The column we want to select.
	 * @param mixed   $conditions Where conditions, you can use array or Compare object.
	 *                            Example:
	 *                            - `array('id' => 5)` => id = 5
	 *                            - `new GteCompare('id', 20)` => 'id >= 20'
	 *                            - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 * @param mixed   $order      Order sort, can ba string, array or object.
	 *                            Example:
	 *                            - `id ASC` => ORDER BY id ASC
	 *                            - `array('catid DESC', 'id')` => ORDER BY catid DESC, id
	 * @param integer $start      Limit start number.
	 * @param integer $limit      Limit rows.
	 *
	 * @return  mixed
	 *
	 * @throws \InvalidArgumentException
	 */
	public function findColumn($column, $conditions = array(), $order = null, $start = null, $limit = null)
	{
		return [];
	}

	/**
	 * Create records by data set.
	 *
	 * @param mixed $dataset The data set contains data we want to store.
	 *
	 * @throws \UnexpectedValueException
	 * @throws \InvalidArgumentException
	 * @return  mixed|DataSet  Data set data with inserted id.
	 */
	public function create($dataset)
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Create one record by data object.
	 *
	 * @param mixed $data Send a data in and store.
	 *
	 * @throws \InvalidArgumentException
	 * @return  mixed|Data Data with inserted id.
	 */
	public function createOne($data)
	{
		$class = $this->getDataClass();

		return new $class;
	}

	/**
	 * Update records by data set. Every data depend on this table's primary key to update itself.
	 *
	 * @param mixed $dataset      Data set contain data we want to update.
	 * @param array $condFields   The where condition tell us record exists or not, if not set,
	 *                            will use primary key instead.
	 * @param bool  $updateNulls  Update empty fields or not.
	 *
	 * @return mixed|DataSet
	 */
	public function update($dataset, $condFields = null, $updateNulls = false)
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Same as update(), just update one row.
	 *
	 * @param mixed $data         The data we want to update.
	 * @param array $condFields   The where condition tell us record exists or not, if not set,
	 *                            will use primary key instead.
	 * @param bool  $updateNulls  Update empty fields or not.
	 *
	 * @return mixed|Data
	 */
	public function updateOne($data, $condFields = null, $updateNulls = false)
	{
		$class = $this->getDataClass();

		return new $class;
	}

	/**
	 * Using one data to update multiple rows, filter by where conditions.
	 * Example:
	 * `$mapper->updateAll(new Data(array('published' => 0)), array('date' => '2014-03-02'))`
	 * Means we make every records which date is 2014-03-02 unpublished.
	 *
	 * @param mixed $data       The data we want to update to every rows.
	 * @param mixed $conditions Where conditions, you can use array or Compare object.
	 *                          Example:
	 *                          - `array('id' => 5)` => id = 5
	 *                          - `new GteCompare('id', 20)` => 'id >= 20'
	 *                          - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 *
	 * @throws \InvalidArgumentException
	 * @return  boolean
	 */
	public function updateBatch($data, $conditions = array())
	{
		return true;
	}

	/**
	 * Flush records, will delete all by conditions then recreate new.
	 *
	 * @param mixed $dataset    Data set contain data we want to update.
	 * @param mixed $conditions Where conditions, you can use array or Compare object.
	 *                          Example:
	 *                          - `array('id' => 5)` => id = 5
	 *                          - `new GteCompare('id', 20)` => 'id >= 20'
	 *                          - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 *
	 * @return  mixed|DataSet Updated data set.
	 */
	public function flush($dataset, $conditions = array())
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Save will auto detect is conditions matched in data or not.
	 * If matched, using update, otherwise we will create it as new record.
	 *
	 * @param mixed $dataset      The data set contains data we want to save.
	 * @param array $condFields   The where condition tell us record exists or not, if not set,
	 *                            will use primary key instead.
	 * @param bool  $updateNulls  Update empty fields or not.
	 *
	 * @return  mixed|DataSet Saved data set.
	 */
	public function save($dataset, $condFields = null, $updateNulls = false)
	{
		$class = $this->getDatasetClass();

		return new $class;
	}

	/**
	 * Save only one row.
	 *
	 * @param mixed $data         The data we want to save.
	 * @param array $condFields   The where condition tell us record exists or not, if not set,
	 *                            will use primary key instead.
	 * @param bool  $updateNulls  Update empty fields or not.
	 *
	 * @return  mixed|Data Saved data.
	 */
	public function saveOne($data, $condFields = null, $updateNulls = false)
	{
		// Event
		$this->triggerEvent('onBefore' . ucfirst(__FUNCTION__), array(
			'data'        => &$data,
			'condFields'  => &$condFields,
			'updateNulls' => &$updateNulls
		));

		$dataset = $this->save($this->bindDataset(array($data)), $condFields, $updateNulls);

		$result = $dataset[0];

		// Event
		$this->triggerEvent('onAfter' . ucfirst(__FUNCTION__), array(
			'result' => &$result,
		));

		return $result;
	}

	/**
	 * Delete records by where conditions.
	 *
	 * @param mixed   $conditions Where conditions, you can use array or Compare object.
	 *                            Example:
	 *                            - `array('id' => 5)` => id = 5
	 *                            - `new GteCompare('id', 20)` => 'id >= 20'
	 *                            - `new Compare('id', '%Flower%', 'LIKE')` => 'id LIKE "%Flower%"'
	 *
	 * @return  boolean Will be always true.
	 */
	public function delete($conditions)
	{
		return true;
	}
}
