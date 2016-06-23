<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractPhoenixController;
use Phoenix\Controller\ControllerResolver as PhoenixControllerResolver;
use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Controller\Controller;
use Windwalker\String\StringNormalise;

/**
 * The AbstractBatchDelegatingController class.
 *
 * @since  1.0
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
		$task = $this->input->get('task', 'Update');

		if (!$task)
		{
			throw new \InvalidArgumentException('Task of: ' . __CLASS__ . ' should not be empty.');
		}

		$resolver = $this->package->getMvcResolver();

		$class = $resolver->resolveController($this->package, $this->getName() . '\Batch\\' . $task . 'Controller');

		if (!$class)
		{
			throw new \DomainException(StringNormalise::toClassNamespace($this->getName() . '\Batch\\' . $task) . 'Controller not found');
		}

		/** @var AbstractController $controller */
		$controller = new $class;
		$controller->setName($this->getName());
		$controller->config->set('item_name', $this->config['item_name']);
		$controller->config->set('list_name', $this->config['list_name']);

		$result = $this->hmvc($controller, array(
			'cid'      => $this->input->getVar('cid'),
			'batch'    => $this->input->getVar('batch'),
			'ordering' => $this->input->getVar('ordering'),
		));

		return $result;
	}
}
