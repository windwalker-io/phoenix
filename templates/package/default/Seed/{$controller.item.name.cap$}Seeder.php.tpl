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
use Windwalker\Core\Seeder\AbstractSeeder;
use Windwalker\Data\Data;
use Windwalker\Filter\OutputFilter;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace -- Ignore seeder file

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
        $faker = $this->faker->create();
        $userIds = range(20, 100);

        foreach (range(1, 150) as $i) {
            $created = $faker->dateTimeThisYear;
            $data    = new Data();

            $data['title']       = (string) str($faker->sentence(2))->trim('.')->toUpperCase();
            $data['alias']       = OutputFilter::stringURLUnicodeSlug($data['title']);
            $data['url']         = $faker->url;
            $data['introtext']   = $faker->paragraph(5);
            $data['fulltext']    = $faker->paragraph(5);
            $data['image']       = $faker->imageUrl();
            $data['state']       = $faker->optional(0.75, 0)->passthrough(1);
            $data['ordering']    = $i;
            $data['created']     = $created->format($this->getDateFormat());
            $data['created_by']  = $faker->randomElement($userIds);
            $data['modified']    = $created->modify('+5 days')->format($this->getDateFormat());
            $data['modified_by'] = $faker->randomElement($userIds);
            $data['language']    = 'en-GB';
            $data['params']      = '';

            {$controller.item.name.cap$}Mapper::createOne($data);

            $this->outCounting();
        }
    }

    /**
     * doClear
     *
     * @return  void
     */
    public function doClear()
    {
        $this->truncate(Table::{$controller.list.name.upper$});
    }
}
