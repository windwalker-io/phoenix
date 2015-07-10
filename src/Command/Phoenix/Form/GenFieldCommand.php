<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Phoenix\Form;

use Windwalker\Console\Command\Command;
use Windwalker\Ioc;

/**
 * The GenFieldCommand class.
 * 
 * @since  {DEPLOY_VERSION}
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

		$this->out()->out('Start Generate Fields')
			->out('--------------------------------------------------')->out()->out();

		foreach ($columns as $column)
		{
			$this->out($this->handleColumn($column));
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

		$className = 'Phoenix\Form\FieldDefinitionGenerator';

		$args = [$type, $name, ucfirst($name), $column];

		$method = 'gen' . ucfirst($type) . ucfirst($name);

		if (!is_callable([$className, $method]))
		{
			$method = 'gen' . ucfirst($name);
		}

		if (!is_callable([$className, $method]))
		{
			$method = 'gen' . ucfirst($type);
		}

		if (!is_callable([$className, $method]))
		{
			$method = 'genVarchar';
		}

		return call_user_func_array([$className, $method], $args) . "\n";
	}
}
