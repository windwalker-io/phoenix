<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Controller\Batch;

use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\Controller\AbstractController;
use Windwalker\String\StringNormalise;

/**
 * The BatchDelegatingController class.
 * 
 * @see  AbstractBatchController
 *
 * @since  1.0
 */
class BatchDelegatingController extends AbstractPhoenixController
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

		$resolver = $this->getPackage()->getMvcResolver();

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
		
		$this->hmvc($controller, $this->input->compact(array(
			'id'       => 'array',
			'batch'    => 'var',
			'ordering' => 'var',
		)));

		return true;
	}
}
