<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\Action;

use Phoenix\Generator\FileOperator\ConvertOperator;
use Windwalker\Filesystem\Folder;
use Windwalker\String\StringHelper;
use Windwalker\Utilities\ArrayHelper;

/**
 * The ConvertTemplateAction class.
 * 
 * @since  1.0
 */
class ConvertTemplateAction extends AbstractAction
{

	/**
	 * Do this execute.
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		/** @var ConvertOperator $operator */
		$operator = $this->container->get('operator.factory')->getOperator('convert');

		$replace = ArrayHelper::flatten($this->replace);

		// Flip replace array because we want to convert template.
		$replace = array_flip($replace);

		foreach ($replace as &$val)
		{
			$val = StringHelper::quote($val, $operator->getTagVariable());
		}

		// Flip src and dest because we want to convert template.
		$src  = $this->config['dir.src'];
		$dest = $this->config['dir.dest'];

		if (is_dir($dest))
		{
			// Remove dir first
			Folder::delete($dest);
		}

		$operator->copy($src, $dest, $replace);
	}
}
