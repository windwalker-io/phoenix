<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Phoenix\Script\JQueryScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Dom\HtmlElement;
use Windwalker\Form\Field\TextField;

/**
 * The ModalField class.
 *
 * @method  mixed|$this  package(string $value = null)
 * @method  mixed|$this  view(string $value = null)
 * @method  mixed|$this  url(string $value = null)
 * @method  mixed|$this  table(string $value = null)
 * @method  mixed|$this  route(string $value = null)
 * @method  mixed|$this  query(string $value = null)
 * @method  mixed|$this  keyField(string $value = null)
 * @method  mixed|$this  titleField(string $value = null)
 * @method  mixed|$this  titleClass(string $value = null)
 * @method  mixed|$this  buttonText(string $value = null)
 * @method  mixed|$this  layout(string $value = null)
 *
 * @since  1.0
 */
class ModalField extends TextField
{
    /**
     * Property table.
     *
     * @var  string
     */
    protected $table;

    /**
     * Property titleField.
     *
     * @var  string
     */
    protected $titleField = 'title';

    /**
     * Property keyName.
     *
     * @var  string
     */
    protected $keyField = 'id';

    /**
     * Property package.
     *
     * @var  string
     */
    protected $package;

    /**
     * Property view.
     *
     * @var  string
     */
    protected $view;

    /**
     * Property route.
     *
     * @var  string
     */
    protected $route;

    /**
     * Property query.
     *
     * @var  array
     */
    protected $query = [];

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  string
     */
    public function buildInput($attrs)
    {
        $this->prepareScript();

        $this->package = $this->get('package', $this->package);
        $this->view    = $this->get('view', $this->view);

        $attribs = $attrs;

        /** @var HtmlElement $input */
        $input                     = parent::buildInput($attribs);
        $input['type']             = 'hidden';
        $input['data-value-store'] = true;

        $url = $this->get('url') ?: $this->getUrl();
        $id  = $this->getId();

        return WidgetHelper::render($this->get('layout', 'phoenix.form.field.modal'), [
            'id' => $id,
            'title' => $this->getTitle(),
            'input' => $input,
            'url' => $url,
            'attrs' => $attrs,
            'field' => $this,
        ], WidgetHelper::EDGE);
    }

    /**
     * getTitle
     *
     * @return  Data
     */
    protected function getTitle()
    {
        $table      = $this->table ?: $this->get('table', $this->view);
        $value      = $this->getValue();
        $keyField   = $this->get('key_field', $this->keyField);
        $titleField = $this->get('title_field', $this->titleField);

        $dataMapper = new DataMapper($table);

        $data = $dataMapper->findOne([$keyField => $value]);

        return $data->$titleField;
    }

    /**
     * getUrl
     *
     * @return  string
     * @throws \OutOfRangeException
     */
    protected function getUrl()
    {
        $package = PackageHelper::getPackage($this->package);

        $package = $package ?: PackageHelper::getPackage();

        $route = $this->get('route', $this->route) ?: $this->view;
        $query = $this->get('query', $this->query);

        return $package->router->route($route, array_merge([
            'layout' => 'modal',
            'selector' => '#' . $this->getId() . '-wrap',
            'function' => $this->get('function', 'Phoenix.Field.Modal.select'),
        ], $query));
    }

    /**
     * prepareScript
     *
     * @return  void
     */
    protected function prepareScript()
    {
        static $inited = false;

        if ($inited) {
            return;
        }

        JQueryScript::ui(['effect']);

        $js = <<<JS
// Phoenix.Field.Modal
var Phoenix;
(function(Phoenix, $) {
    (function() {
        Phoenix.Field.Modal = {
            select: function(selector, id, title) {
                var ele = $(selector);

                ele.find('.input-group input').attr('value', title).trigger('change').delay(250).effect('highlight');
                ele.find('input[data-value-store]').attr('value', id).trigger('change');

                $('#phoenix-iframe-modal').modal('hide');
            }
        };
    })(Phoenix.Field || (Phoenix.Field = {}));
})(Phoenix || (Phoenix = {}), jQuery);
JS;

        Asset::internalScript($js);

        $inited = true;
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'package',
            'view',
            'url',
            'table',
            'route',
            'query',
            'keyField' => 'key_field',
            'titleField' => 'title_field',
            'titleClass',
            'buttonText',
            'layout',
        ]);
    }
}
