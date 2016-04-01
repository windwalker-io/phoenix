<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Record;

use Windwalker\Database\DatabaseFactory;
use Windwalker\Database\Driver\AbstractDatabaseDriver;
use Windwalker\Ioc;
use Windwalker\Record\Record;

/**
 * The RecordHelper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class RecordHelper
{
	/**
	 * Database adapter.
	 *
	 * @var  AbstractDatabaseDriver
	 */
	protected $db = null;

	/**
	 * The table name.
	 *
	 * @var  string
	 */
	protected $table = null;

	/**
	 * The primary key name.
	 *
	 * @var  string
	 */
	protected $pkName = null;

	/**
	 * Class init.
	 *
	 * @param string                 $table  The table name.
	 * @param AbstractDatabaseDriver $db     Database adapter.
	 * @param string                 $pkName The primary key name.
	 */
	public function __construct($table, AbstractDatabaseDriver $db = null, $pkName = 'id')
	{
		$this->db = $db ? : $this->getDb();
		$this->table = $table;
		$this->pkName = $pkName;
	}

	/**
	 * initNewRow
	 *
	 * @param string|Record           $table
	 * @param string|integer          $id
	 * @param array                   $row
	 * @param string                  $keys
	 * @param AbstractDatabaseDriver  $db
	 *
	 * @return  bool|string|int Return init id.
	 */
	public static function initNewRow($table, $id, $row = array(), $keys = 'id', AbstractDatabaseDriver $db = null)
	{
		if ($table instanceof Record)
		{
			$keys  = $table->getKeyName();
			$db    = $table->getDb();
			$table = $table->getTableName();
		}

		$db = $db ? : DatabaseFactory::getDbo();

		$helper = new static($table, $db, $keys);

		return $helper->initRow($id, $row);
	}

	/**
	 * Initialise a new record and return id.
	 *
	 * @param int|string  $id   The id to find.
	 * @param mixed       $row  The added row.
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return  bool|string|int Return init id.
	 */
	public function initRow($id, $row = array())
	{
		if ($this->exists($id))
		{
			return $id;
		}

		$row = $row ? $row : array();

		$row[$this->pkName] = $id;

		if (! $this->db->getWriter()->insertOne($this->table, $row, $this->pkName))
		{
			return false;
		}

		return $row[$this->pkName];
	}

	/**
	 * Is an id exists?
	 *
	 * @param int|string $id The id to find.
	 *
	 * @return  boolean True if exists.
	 */
	public function exists($id)
	{
		$query = $this->db->getQuery(true);

		$query->select($this->pkName)
			->from($this->table)
			->where($query->format('%n = %q', $this->pkName, $id));

		if ($this->db->setQuery($query)->loadResult())
		{
			return true;
		}

		return false;
	}

	/**
	 * Get DB adapter.
	 *
	 * @return  AbstractDatabaseDriver The DB adapter.
	 */
	public function getDb()
	{
		if (!$this->db)
		{
			$this->db = Ioc::getDatabase();
		}

		return $this->db;
	}

	/**
	 * Set DB adapter.
	 *
	 * @param   AbstractDatabaseDriver $db
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setDb($db)
	{
		$this->db = $db;

		return $this;
	}
}
