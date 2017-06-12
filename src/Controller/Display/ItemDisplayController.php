<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Display;

use Phoenix\Model\ItemModel;
use Phoenix\View\ItemView;
use Windwalker\Core\Model\ModelRepository;

/**
 * The GetController class.
 *
 * @method  ItemModel getModel($name = null, $source = null, $forceNew)
 * @method  ItemView  getView($name = null, $format = 'html', $engine = null, $forceNew = false)
 * 
 * @since  1.0
 */
class ItemDisplayController extends DisplayController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::SINGULAR;

	/**
	 * Property keyName.
	 *
	 * @var  string
	 */
	protected $keyName = 'id';

	/**
	 * prepareExecute
	 *
	 * @param ModelRepository $model
	 *
	 * @return void
	 *
	 * @deprecated Override prepareViewModel() instead.
	 */
	protected function prepareModelState(ModelRepository $model)
	{
		$model['load.conditions'] = $this->input->get($this->keyName);
	}
}
