<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Controller\Package;

use Phoenix\Generator\Action;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Filesystem\File;
use Windwalker\Filesystem\Folder;

/**
 * The ConvertController class.
 * 
 * @since  1.0
 */
class ConvertController extends AbstractPackageController
{

	/**
	 * Execute the controller.
	 *
	 * @return  boolean  True if controller finished execution, false if the controller did not
	 *                   finish execution. A controller might return false if some precondition for
	 *                   the controller to run has not been satisfied.
	 *
	 * @throws  \LogicException
	 * @throws  \RuntimeException
	 */
	public function execute()
	{
		// Flip src and dest because we want to convert template.
		$dest = $this->config->get('dir.dest');
		$src  = $this->config->get('dir.src');

		$this->config->set('dir.dest', $src);
		$this->config->set('dir.src',  $dest);

		$this->doAction(new Action\ConvertTemplateAction);

		// Rename migration
		if (is_dir($src . '/Migration'))
		{
			$file = array_shift(Folder::files($src . '/Migration'));

			$time = '19700101000000';

			$newFilename = preg_replace('/\d+(_.+Init\.php\.tpl)/', $time . '$1', $file);

			File::move($file, $newFilename);
		}

		return true;
	}
}
