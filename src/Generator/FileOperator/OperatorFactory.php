<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Generator\FileOperator;

use Muse\FileOperator\AbstractFileOperator;
use Muse\IO\IOInterface;

/**
 * The OperatorFactory class.
 * 
 * @since  1.0
 */
class OperatorFactory
{
	/**
	 * Property io.
	 *
	 * @var  IOInterface
	 */
	protected $io;

	/**
	 * Default {@...@} to prevent twig conflict.
	 *
	 * @var  array
	 */
	protected $tagVariable = [];

	/**
	 * Class init.
	 *
	 * @param  IOInterface   $io
	 * @param  array|string  $tagVariable
	 */
	public function __construct(IOInterface $io, $tagVariable)
	{
		$this->io = $io;
		$this->tagVariable = (array) $tagVariable;
	}

	/**
	 * getOperator
	 *
	 * @param string $type
	 * @param array  $tagVariable
	 *
	 * @return  AbstractFileOperator
	 */
	public function getOperator($type, $tagVariable = null)
	{
		$tagVariable = $tagVariable ? : $this->tagVariable;

		$class = sprintf('Phoenix\Generator\FileOperator\%sOperator', ucfirst($type));

		if (!class_exists($class))
		{
			$class = sprintf('Muse\FileOperator\%sOperator', ucfirst($type));
		}

		if (!class_exists($class))
		{
			throw new \DomainException(sprintf('FileOperator: %s not supported.', $type));
		}

		return new $class($this->io, $tagVariable);
	}

	/**
	 * Method to get property Io
	 *
	 * @return  IOInterface
	 */
	public function getIO()
	{
		return $this->io;
	}

	/**
	 * Method to set property io
	 *
	 * @param   IOInterface $io
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setIO($io)
	{
		$this->io = $io;

		return $this;
	}

	/**
	 * Method to get property TagVariable
	 *
	 * @return  array
	 */
	public function getTagVariable()
	{
		return $this->tagVariable;
	}

	/**
	 * Method to set property tagVariable
	 *
	 * @param   array $tagVariable
	 *
	 * @return  static  Return self to support chaining.
	 */
	public function setTagVariable($tagVariable)
	{
		$this->tagVariable = $tagVariable;

		return $this;
	}
}
