<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Model\FormModel;
use Windwalker\Core\Language\Translator;

/**
 * The EditView class.
 *
 * @since  1.0
 */
class EditView extends ItemView
{
	/**
	 * Property formDefinition.
	 *
	 * @var  string
	 */
	protected $formDefinition = 'edit';

	/**
	 * Property formControl.
	 *
	 * @var  string
	 */
	protected $formControl = 'item';

	/**
	 * Property formLoadData.
	 *
	 * @var  boolean
	 */
	protected $formLoadData = true;

	/**
	 * setTitle
	 *
	 * @param string $title
	 *
	 * @return  static
	 */
	public function setTitle($title = null)
	{
		$title = $title ? : Translator::sprintf('phoenix.title.edit', Translator::translate($this->langPrefix . $this->getName() . '.title'));

		return parent::setTitle($title);
	}

	/**
	 * prepareRender
	 *
	 * @param \Windwalker\Data\Data $data
	 *
	 * @return  void
	 */
	protected function prepareRender($data)
	{
		parent::prepareRender($data);

		// TODO: Test if we need a exception or not.
//		$mdoel = $this->model->getModel();
//
//		if (!$mdoel instanceof FormModel)
//		{
//			throw new \UnexpectedValueException('You must use FormModel in EditView');
//		}

		$data->form = $this->model->getForm($this->formDefinition, $this->formControl, $this->formLoadData);
	}
}
