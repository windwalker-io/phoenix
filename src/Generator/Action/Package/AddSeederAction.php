<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator\Action\Package;

use Muse\Action\AbstractAction;
use Phoenix\Generator\GeneratorHelper;

/**
 * The AddSeederAction class.
 *
 * @since  1.0
 */
class AddSeederAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$name = $this->config['replace.controller.item.name.cap'];

		$file = $this->config['dir.dest'] . '/Seed/MainSeeder.php';

		if (!is_file($file))
		{
			return;
		}

		$code = file_get_contents($file);
		$added = false;

		if (strpos($code, '$this->execute(' . $name . 'Seeder::class);') === false)
		{
			$replace = "\t\t\$this->execute({$name}Seeder::class);\n\n";

			$code = GeneratorHelper::addBeforePlaceholder('seeder-execute', $code, $replace);

			$added = true;
		}

		if (strpos($code, '$this->clear(' . $name . 'Seeder::class);') === false)
		{
			$replace = "\t\t\$this->clear({$name}Seeder::class);\n\n";

			$code = GeneratorHelper::addBeforePlaceholder('seeder-clear', $code, $replace);

			$added = true;
		}

		if ($added)
		{
			file_put_contents($file, $code);

			$this->io->out('[<info>Action</info>] Add seeder to MainSeeder');
		}
	}
}
