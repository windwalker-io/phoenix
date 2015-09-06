<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractPhoenixController;
use Phoenix\Controller\ControllerResolver as PhoenixControllerResolver;
use Windwalker\Core\Controller\Controller;

/**
 * The AbstractBatchDelegatingController class.
 *
 * @since  {DEPLOY_VERSION}
 */
class BatchDelegationController extends AbstractPhoenixController
{
	/**
	 * Property inflection.
	 *
	 * @var  string
	 */
	protected $inflection = self::PLURAL;

	/**
	 * doExecute
	 *
	 * @return  mixed
	 */
	protected function doExecute()
	{
		$task = $this->input->get('task', 'Move');

		if (!$task)
		{
			throw new \InvalidArgumentException('Task of: ' . __CLASS__ . ' should not be empty.');
		}

		$class = PhoenixControllerResolver::getController($this->package, 'Batch\\' . $task, $this->getName());

		/** @var Controller $controller */
		$controller = new $class;
		$controller->setName($this->getName());
		$controller->config->set('item_name', $this->config['item_name']);
		$controller->config->set('list_name', $this->config['list_name']);

		$result = $this->hmvc($controller, array(
			'cid'      => $this->input->getVar('cid'),
			'batch'    => $this->input->getVar('batch'),
			'ordering' => $this->input->getVar('ordering'),
		));

		list($url, $msg, $type) = $controller->getRedirect(true);

		$this->setRedirect($url, $msg, $type);

		return $result;
	}
}
