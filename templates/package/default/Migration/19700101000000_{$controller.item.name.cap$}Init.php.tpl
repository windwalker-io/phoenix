<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Core\Migration\Schema;
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
		$this->getTable(Table::{$controller.list.name.upper$}, function(Schema $sc)
		{
			$sc->addColumn('id',          new Column\Primary)->comment('Primary Key');
			$sc->addColumn('title',       new Column\Varchar)->comment('Title');
			$sc->addColumn('alias',       new Column\Varchar)->comment('Alias');
			$sc->addColumn('url',         new Column\Varchar)->comment('URL');
			$sc->addColumn('introtext',   new Column\Text)->comment('Intro Text');
			$sc->addColumn('fulltext',    new Column\Text)->comment('Full Text');
			$sc->addColumn('image',       new Column\Varchar)->comment('Main Image');
			$sc->addColumn('state',       new Column\Tinyint)->signed(true)->comment('0: unpublished, 1:published');
			$sc->addColumn('ordering',    new Column\Integer)->comment('Ordering');
			$sc->addColumn('version',     new Column\Integer)->comment('Version');
			$sc->addColumn('created',     new Column\Datetime)->comment('Created Date');
			$sc->addColumn('created_by',  new Column\Integer)->comment('Author');
			$sc->addColumn('modified',    new Column\Datetime)->comment('Modified Date');
			$sc->addColumn('modified_by', new Column\Integer)->comment('Modified User');
			$sc->addColumn('language',    new Column\Char)->length(7)->comment('Language');
			$sc->addColumn('params',      new Column\Text)->comment('Params');

			$sc->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_alias', 'alias');
			$sc->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_language', 'language');
			$sc->addIndex(Key::TYPE_INDEX, 'idx_{$controller.list.name.lower$}_created_by', 'created_by');
		})->create(true);
	}

	/**
	 * Migrate Down.
	 */
	public function down()
	{
		$this->drop(Table::{$controller.list.name.upper$});
	}
}
