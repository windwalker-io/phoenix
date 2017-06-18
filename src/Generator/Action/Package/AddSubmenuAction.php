<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action\Package;

use Phoenix\Generator\Action\AbstractAction;
use Phoenix\Generator\GeneratorHelper;

/**
 * The AddSubmenuAction class.
 *
 * @since  1.0
 */
class AddSubmenuAction extends AbstractAction
{
	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$name = $this->config['replace.controller.list.name.lower'];
		$package = $this->config['replace.package.name.lower'];
		$file = $this->config['dir.dest'] . '/Templates/_global/' . $this->config['replace.package.name.lower'] . '/widget/submenu.blade.php';

		if (!is_file($file))
		{
			return;
		}

		$code = file_get_contents($file);

		if (strpos($code, "\$helper->menu->active('$name')") !== false)
		{
			return;
		}

		$replace = <<<HTML
	<li class="{{ \$helper->menu->active('$name') }}">
		<a href="{{ \$router->route('$name') }}">
	        @translate('$package.$name.title')
	    </a>
	</li>


HTML;

		$code = GeneratorHelper::addBeforePlaceholder('submenu', $code, $replace, '{{--');

		file_put_contents($file, $code);

		$this->io->out('[<info>Action</info>] Add menu item: ' . $name . ' submenu.');

	}
}
