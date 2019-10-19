<?php

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\CustomHtmlField;

/**
 * The LayoutField class.
 *
 * @method  mixed|$this  layout(string|callable $value = null)
 * @method  mixed|$this  variables(array $value = null)
 * @method  mixed|$this  engine(string $value = null)
 * @method  mixed|$this  package($value = null)
 *
 * @since  __DEPLOY_VERSION__
 */
class LayoutField extends CustomHtmlField
{
    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  mixed
     */
    public function buildInput($attrs)
    {
        $layout = $this->layout();

        if ($layout) {
            if (is_callable($layout)) {
                $this->content($layout);
            } else {
                $html = WidgetHelper::render(
                    $this->layout(),
                    $this->variables() ?: [],
                    $this->engine() ?: 'engine',
                    $this->package()
                );

                $this->content($html);
            }
        }

        return parent::buildInput($attrs);
    }

    /**
     * render
     *
     * @param string                      $layout
     * @param array                       $data
     * @param string                      $engine
     * @param null|AbstractPackage|string $package
     *
     * @return  $this
     *
     * @since  __DEPLOY_VERSION__
     */
    public function renderLayout(string $layout, array $data = [], string $engine = 'edge', $package = null): self
    {
        $this->set('layout', $layout)
            ->set('variables', $data)
            ->set('engine', $engine)
            ->set('package', $package);

        return $this;
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'layout',
            'variables',
            'engine',
            'package',
        ]);
    }
}
