<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Windwalker\Core\Seeder\AbstractSeeder;

/**
 * The MainSeeder class.
 * 
 * @since  1.0
 */
class MainSeeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$this->execute({$controller.item.name.cap$}Seeder::class);

		// @muse-placeholder  seeder-execute  Do not remove this.
	}

	/**
	 * doClear
	 *
	 * @return  void
	 */
	public function doClear()
	{
		$this->clear({$controller.item.name.cap$}Seeder::class);

		// @muse-placeholder  seeder-clear  Do not remove this.
	}
}
