<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

return [
	'providers' => [

	],

	'routing' => [
		'files' => [
			'main' => PACKAGE_{$package.name.upper$}_ROOT . '/routing.yml'
		]
	],

	'middlewares' => [

	],

	'configs' => [

	],

	'listeners' => [
		'orphans' => \Phoenix\Listener\DumpOrphansListener::class
	],

	'console' => [
		'commands' => [

		]
	]
];
