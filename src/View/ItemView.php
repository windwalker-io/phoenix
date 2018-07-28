<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

/**
 * The ItemView class.
 *
 * @since  1.0
 */
class ItemView extends AbstractPhoenixHtmView
{
    /**
     * setTitle
     *
     * @param string $title
     *
     * @return  static
     */
    public function setTitle($title = null)
    {
        $title = $title ?: __(
            'phoenix.title.item',
            __($this->langPrefix . $this->getName() . '.title')
        );

        return parent::setTitle($title);
    }

    /**
     * prepareRender
     *
     * @param \Windwalker\Data\Data $data
     *
     * @return  void
     */
    protected function prepareData($data)
    {
        parent::prepareData($data);

        $data->item = $data->item ?: $this->model->getItem();
    }
}
