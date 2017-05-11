<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix\Form;

use Phoenix\Form\FieldDefinitionGenerator;
use Windwalker\Console\Command\Command;
use Windwalker\Filesystem\Folder;
use Windwalker\Ioc;

/**
 * The GenFieldCommand class.
 * 
 * @since  1.0
 */
class GenFieldCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'gen-field';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Generate fields definition';

	/**
	 * init
	 *
	 * @return  void
	 */
	protected function init()
	{
		$this->addOption('o')
			->alias('output')
			->description('Output file');
	}

	/**
	 * doExecute
	 *
	 * @return  bool
	 */
	protected function doExecute()
	{
		$table = $this->getArgument(0);

		if (!$table)
		{
			throw new \InvalidArgumentException('No table');
		}

		$db = Ioc::getDatabase();

		$columns = $db->getTable($table)->getColumnDetails();

		if ($file = $this->getOption('o'))
		{
			if ($file == 1)
			{
				$file = '/form/fields/' . $table . '.php.tpl';
			}

			$file = new \SplFileInfo(WINDWALKER_TEMP . '/' . ltrim($file, '/\\'));

			if (!is_dir($file))
			{
				Folder::create($file->getPath());
			}

			$output = '';

			foreach ($columns as $column)
			{
				$output .= $this->handleColumn($column) . "\n";
			}

			file_put_contents($file->getPathname(), $output);

			$this->out()->out('File output to: ' . $file->getPathname());
		}
		else
		{
			$this->out()->out('Start Generate Fields')
				->out('--------------------------------------------------')->out()->out();

			foreach ($columns as $column)
			{
				$this->out($this->handleColumn($column));
			}
		}

		return true;
	}

	/**
	 * handleColumn
	 *
	 * @param array $column
	 *
	 * @return  mixed
	 */
	protected function handleColumn($column)
	{
		$type = explode('(', $column->Type);

		$type = $type[0];

		$name = $column->Field;

		$className = $this->getOption('class', FieldDefinitionGenerator::class);

		if (!is_callable([$className, 'generate']))
		{
			throw new \LogicException('Method: ' . $className . "::generate() can not execute.");
		}

		return call_user_func([$className, 'generate'], $type, $name, $column);
	}
}
