<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use {$package.namespace$}{$package.name.cap$}\DataMapper\{$controller.item.name.cap$}Mapper;
use {$package.namespace$}{$package.name.cap$}\Table\Table;
use Faker\Factory;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Seeder\AbstractSeeder;
use Windwalker\Data\Data;
use Windwalker\Filter\OutputFilter;

/**
 * The {$controller.item.name.cap$}Seeder class.
 * 
 * @since  1.0
 */
class {$controller.item.name.cap$}Seeder extends AbstractSeeder
{
	/**
	 * doExecute
	 *
	 * @return  void
	 */
	public function doExecute()
	{
		$faker = Factory::create();

		foreach (range(1, 30) as $i)
		{
			$data = new Data;

			$data['title']       = $faker->sentence(rand(3, 5));
			$data['alias']       = OutputFilter::stringURLSafe($data['title']);
			$data['url']         = $faker->url;
			$data['introtext']   = $faker->paragraph(5);
			$data['fulltext']    = $faker->paragraph(5);
			$data['image']       = $faker->imageUrl();
			$data['state']       = $faker->randomElement(array(1, 1, 1, 1, 0, 0));
			$data['version']     = rand(1, 50);
			$data['created']     = $faker->dateTime->format(DateTime::FORMAT_SQL);
			$data['created_by']  = rand(20, 100);
			$data['modified']    = $faker->dateTime->format(DateTime::FORMAT_SQL);
			$data['modified_by'] = rand(20, 100);
			$data['ordering']    = $i;
			$data['language']    = 'en-GB';
			$data['params']      = '';

			{$controller.item.name.cap$}Mapper::createOne($data);

			$this->command->out('.', false);
		}

		$this->command->out();
	}

	/**
	 * doClean
	 *
	 * @return  void
	 */
	public function doClear()
	{
		$this->truncate(Table::{$controller.list.name.upper$});
	}
}
