<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Repository\FormAwareRepositoryInterface;

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
        $title = $title ?: __('phoenix.title.edit', __($this->langPrefix . $this->getName() . '.title'));

        return parent::setTitle($title);
    }

    /**
     * prepareRender
     *
     * @param \Windwalker\Data\Data $data
     *
     * @return  void
     * @throws \UnexpectedValueException
     */
    protected function prepareData($data)
    {
        parent::prepareData($data);

        $model = $this->model->getModel();

        if (!$model instanceof FormAwareRepositoryInterface) {
            throw new \UnexpectedValueException('You must use a Model implemented ' . FormAwareRepositoryInterface::class . ' in EditView');
        }

        $data->form = $data->form
            ?: $this->model->getForm($this->formDefinition, $this->formControl, $this->formLoadData);
    }
}
