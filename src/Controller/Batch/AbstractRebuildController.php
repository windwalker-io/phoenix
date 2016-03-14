<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Language\Translator;
use Windwalker\Record\NestedRecord;

/**
 * The AbstractRebuildController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class AbstractRebuildController extends AbstractBatchController
{
	/**
	 * Property record.
	 *
	 * @var  NestedRecord
	 */
	protected $record;

	/**
	 * Property action.
	 *
	 * @var  string
	 */
	protected $action = 'rebuild';

	/**
	 * doExecute
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	protected function doExecute()
	{
		try
		{
			if (!$this->checkToken())
			{
				throw new \RuntimeException('Invalid Token');
			}

			$this->record->rebuild();

			$ids = $this->model->getDataMapper()->findColumn('id', array('parent_id != 0'));

			foreach ($ids as $id)
			{
				$this->record->rebuildPath($id);
			}
		}
		catch (\Exception $e)
		{
			if (WINDWALKER_DEBUG)
			{
				throw $e;
			}

			$this->setRedirect($this->getFailRedirect(), $e->getMessage(), Bootstrap::MSG_DANGER);
		}

		$msg = Translator::translate($this->langPrefix . 'message.batch.' . $this->action . '.success');

		$this->setRedirect($this->getSuccessRedirect(), $msg, Bootstrap::MSG_SUCCESS);

		return true;
	}
}
