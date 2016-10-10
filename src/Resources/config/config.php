<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

return [
	'providers' => [
		\Phoenix\Provider\PhoenixProvider::class
	],

	'console' => [
		'commands' => [
			\Phoenix\Command\MuseCommand::class,
			\Phoenix\Command\PhoenixCommand::class
		]
	]
];
