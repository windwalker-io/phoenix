<?php
/**
 * Part of Windwalker project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Command\Muse;

use Phoenix\Symlink\Symlink;
use Windwalker\Console\Command\Command;
use Windwalker\Filesystem\Folder;

/**
 * The LinkCommand class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class LinkCommand extends Command
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'link';

	/**
	 * Property description.
	 *
	 * @var  string
	 */
	protected $description = 'Link to template.';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	protected function initialise()
	{
	}

	/**
	 * doExecute
	 *
	 * @return  boolean
	 */
	protected function doExecute()
	{
		$tmpl = $this->getArgument(0, 'Flower');

		$src = PHOENIX_TEMPLATES . DIRECTORY_SEPARATOR . $tmpl;

		$dest = WINDWALKER_SOURCE . DIRECTORY_SEPARATOR . $this->getArgument(1, $tmpl);

		if (!is_dir($src))
		{
			throw new \RuntimeException('Template ' . $tmpl . ' not exists');
		}

		if (!is_dir(dirname($dest)))
		{
			Folder::create(dirname($dest));
		}

		Symlink::make($src, $dest);

		$this->out('Make link ' . $src . ' to ' . $dest);

		return true;
	}
}
