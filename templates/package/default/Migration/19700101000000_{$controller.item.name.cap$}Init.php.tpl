<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Database\Schema\Column;
use Windwalker\Database\Schema\DataType;
use Windwalker\Database\Schema\Key;

/**
 * Migration class of {$controller.item.name.cap$}Init.
 */
class {$controller.item.name.cap$}Init extends AbstractMigration
{
	/**
	 * Migrate Up.
	 */
	public function up()
	{
		$this->db->getTable(Table::{$controller.list.name.upper$}, true)
			->addColumn(new Column\Primary('id'))
			->addColumn(new Column\Varchar('title'))
			->addColumn(new Column\Varchar('alias'))
			->addColumn(new Column\Varchar('url'))
			->addColumn(new Column\Text('introtext'))
			->addColumn(new Column\Text('fulltext'))
			->addColumn(new Column\Text('images'))
			->addColumn(new Column\Integer('version'))
			->addColumn(new Column\Datetime('created'))
			->addColumn(new Column\Integer('created_by'))
			->addColumn(new Column\Datetime('modified'))
			->addColumn(new Column\Integer('modified_by'))
			->addColumn(new Column\Integer('ordering'))
			->addColumn(new Column\Tinyint('state'))
			->addColumn(new Column\Char('language', 7))
			->addColumn(new Column\Text('params'))
			->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_alias', 'alias')
			->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_language', 'language')
			->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_created_by', 'created_by')
			->create(true);
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$this->db->getTable(Table::{$controller.list.name.upper$}, true)->drop(true);
	}
}
