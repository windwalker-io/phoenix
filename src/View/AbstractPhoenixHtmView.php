<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\View;

use Phoenix\Html\HtmlHeader;
use Windwalker\Core\Renderer\RendererHelper;
use Windwalker\Core\View\HtmlView;
use Windwalker\Data\Data;
use Windwalker\Renderer\AbstractRenderer;

/**
 * The AbstractRadHtmView class.
 *
 * @since  1.0
 */
abstract class AbstractPhoenixHtmView extends HtmlView
{
    /**
     * Property renderer.
     *
     * @var  string|AbstractRenderer
     */
    protected $renderer = RendererHelper::EDGE;

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix;

    /**
     * Method to get property LangPrefix
     *
     * @return  string
     *
     * @since  1.8.13
     */
    public function getLangPrefix(): ?string
    {
        return $this->langPrefix;
    }

    /**
     * Method to set property langPrefix
     *
     * @param string $langPrefix
     *
     * @return  static  Return self to support chaining.
     *
     * @since  1.8.13
     */
    public function setLangPrefix(?string $langPrefix)
    {
        $this->langPrefix = $langPrefix;

        return $this;
    }

    /**
     * prepareRender
     *
     * @param   Data $data
     *
     * @return  void
     */
    protected function prepareRender($data)
    {
        $data->state = $data->state ?: $this->repository->getState();

        $this->langPrefix = $this->langPrefix ?: $this->getPackage()->getName() . '.';

        $this->setTitle();
    }

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return  static
     */
    public function setTitle($title = null)
    {
        HtmlHeader::setTitle($title);

        return $this;
    }

    /**
     * getTitle
     *
     * @return  string
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getTitle(): string
    {
        return HtmlHeader::getTitle();
    }
}
