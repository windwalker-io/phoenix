<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Field;

use Phoenix\PhoenixPackage;
use Phoenix\Script\CoreScript;
use Phoenix\Script\JQueryScript;
use Phoenix\Script\PhoenixScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Cache\RuntimeCacheTrait;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Data;
use Windwalker\Data\DataSet;
use Windwalker\DataMapper\DataMapper;
use Windwalker\Dom\HtmlElement;
use Windwalker\Dom\HtmlElements;
use Windwalker\Form\Field\AbstractField;
use Windwalker\Html\Option;

/**
 * The ModalField class.
 *
 * @method  mixed|$this  package(string $value = null)
 * @method  mixed|$this  view(string $value = null)
 * @method  mixed|$this  url(string $value = null)
 * @method  mixed|$this  function(string $value = null)
 * @method  mixed|$this  table(string $value = null)
 * @method  mixed|$this  route(string $value = null)
 * @method  mixed|$this  query(string $value = null)
 * @method  mixed|$this  keyField(string $value = null)
 * @method  mixed|$this  titleField(string $value = null)
 * @method  mixed|$this  imageField(string $value = null)
 * @method  mixed|$this  titleClass(string $value = null)
 * @method  mixed|$this  buttonText(string $value = null)
 * @method  mixed|$this  layout(string $value = null)
 * @method  mixed|$this  multiple(bool $value = null)
 * @method  mixed|$this  listType(string $value = null)
 * @method  mixed|$this  itemTemplate(string $value = null)
 * @method  mixed|$this  hasImage(bool $value = null)
 * @method  mixed|$this  sortable(bool $value = null)
 * @method  mixed|$this  placeholder(string $value = null)
 * @method  mixed|$this  onchange(string $value = null)
 * @method  mixed|$this  onfocus(string $value = null)
 * @method  mixed|$this  onblur(string $value = null)
 * @method  mixed|$this  height(int $value = null)
 *
 * @since  1.0
 */
class ModalField extends AbstractField
{
    use RuntimeCacheTrait;

    const TYPE_TAG = 'tag';
    const TYPE_LIST = 'list';

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
     * Property imageField.
     *
     * @var  string
     */
    protected $imageField = 'image';

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
     * prepareRenderInput
     *
     * @param array $attrs
     *
     * @return  void
     */
    public function prepare(&$attrs)
    {
        $attrs['type'] = $this->type ?: 'hidden';
        $attrs['name'] = $this->getFieldName();
        $attrs['id'] = $this->getAttribute('id', $this->getId());
        $attrs['placeholder'] = $this->getAttribute('placeholder');
        $attrs['class'] = $this->getAttribute('class');
        $attrs['readonly'] = $this->getAttribute('readonly');
        $attrs['disabled'] = $this->getAttribute('disabled');
        $attrs['onchange'] = $this->getAttribute('onchange');
        $attrs['onfocus'] = $this->getAttribute('onfocus');
        $attrs['onblur'] = $this->getAttribute('onblur');
        $attrs['value'] = $this->getValue();

        $attrs['required'] = $this->required;
    }

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \Exception
     */
    public function buildInput($attrs)
    {
        $this->prepareScript();

        $this->package = $this->get('package', $this->package);
        $this->view    = $this->get('view', $this->view);
        $multiple = $this->get('multiple');
        $title = '';
        $image = '';

        $attribs = $attrs;

        /** @var HtmlElement $input */
        $input                     = parent::buildInput($attribs);
        $input['type']             = 'hidden';
        $input['data-value-store'] = true;

        $items = [];

        if ($multiple) {
            $this->def('list_type', static::TYPE_TAG);

            if ($this->listType() === static::TYPE_TAG) {
                $input->setName('select');
                $input['multiple'] = true;
                $input['name'] .= '[]';
                unset($input['type'], $input['value']);

                $items = $this->getItems();

                $options = new HtmlElements();

                foreach ($items as $item) {
                    $options[] = new Option($item->title, $item->value, ['selected' => true]);
                }

                $input->setContent($options);
            }
        } else {
            $title = $this->getTitle();

            if ($this->hasImage()) {
                $image = (string) $this->getImage();
            }
        }

        $url = $this->get('url') ?: $this->getUrl();
        $id  = $this->getId();

        $defaultLayout = $this->get('multiple') ? 'phoenix.form.field.modal-multiple' : 'phoenix.form.field.modal';

        return WidgetHelper::render($this->get('layout', $defaultLayout), [
            'id' => $id,
            'title' => $title, // For single
            'image' => $image, // For single
            'input' => $input,
            'url' => $url,
            'attrs' => $attrs,
            'field' => $this,
            'items' => $items
        ], WidgetHelper::EDGE);
    }

    /**
     * getTitle
     *
     * @return  Data
     * @throws \Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getTitle()
    {
        $titleField = $this->get('title_field', $this->titleField);

        return $this->getItem()->$titleField;
    }

    /**
     * getImage
     *
     * @return  string
     *
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getImage()
    {
        $imageField = $this->get('image_field', $this->imageField);

        return $this->getItem()->$imageField;
    }

    /**
     * getItem
     *
     * @return  Data
     *
     * @throws \Exception
     * @throws \Psr\Cache\InvalidArgumentException
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getItem()
    {
        return $this->fetch('item', function () {
            $table      = $this->table ?: $this->get('table', $this->view);
            $value      = $this->getValue();
            $keyField   = $this->get('key_field', $this->keyField);

            $dataMapper = new DataMapper($table);

            return $dataMapper->findOne([$keyField => $value]);
        });
    }

    /**
     * getItems
     *
     * @return  Data[]
     *
     * @throws \Exception
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function getItems()
    {
        $table    = $this->table ?: $this->get('table', $this->view);
        $value    = $this->getValue();
        $keyField = $this->get('key_field', $this->keyField);

        if (is_string($value)) {
            $value = array_filter(explode(',', $value), 'strlen');
        }

        $value = (array) $value;

        if ($value === []) {
            return [];
        }

        $dataMapper = new DataMapper($table);

        $items = $dataMapper->find([$keyField => $value], null, null, null, $keyField);

        $items = $this->prepareItems($items);

        if (!$this->sortable()) {
            return array_values($items->dump());
        }

        $sortedItems = [];

        foreach ($value as $id) {
            if ($items->offsetExists($id)) {
                $sortedItems[$id] = $items[$id];
            }
        }

        return array_values($sortedItems);
    }

    /**
     * prepareItems
     *
     * @param DataSet|Data[] $items
     *
     * @return  DataSet|Data[]
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function prepareItems(DataSet $items)
    {
        $keyField   = $this->get('key_field', $this->keyField);
        $titleField = $this->get('title_field', $this->titleField);
        $imageField = $this->get('image_field', $this->imageField);

        foreach ($items as $item) {
            $item->title = $item->$titleField;
            $item->value = $item->$keyField;
            $item->image = $item->$imageField;
        }

        return $items;
    }

    /**
     * getUrl
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getUrl()
    {
        $package = PackageHelper::getPackage($this->package);

        $package = $package ?: PackageHelper::getPackage();

        $route = $this->get('route', $this->route) ?: $this->view;
        $query = $this->get('query', $this->query);

        $defaultFunction = 'Phoenix.Field.Modal.select';

        if ($this->get('multiple')) {
            if ($this->listType() === static::TYPE_TAG) {
                $defaultFunction = 'Phoenix.Field.Modal.selectAsTag';
            } else {
                $defaultFunction = 'Phoenix.Field.Modal.selectAsList';
            }
        }

        return $package->router->route($route, array_merge([
            'layout' => 'modal',
            'selector' => '#' . $this->getId() . '-wrap',
            'function' => $this->get('function', $defaultFunction),
        ], $query));
    }

    /**
     * prepareScript
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareScript()
    {
        static $inited = false;

        if (!$inited) {
            JQueryScript::ui(['effect']);
            CoreScript::underscore();
            PhoenixScript::translate('phoenix.form.field.modal.already.selected');
            Asset::addJS(PackageHelper::getAlias(PhoenixPackage::class) . '/js/field/modal-field.min.js');

            $inited = true;
        }

        if ($this->multiple()) {
            if ($this->listType() === static::TYPE_LIST) {
                $options = json_encode([
                    'itemTemplate' => $this->itemTemplate() ?: '#' . $this->getId() . '-item-tmpl'
                ]);

                $items = json_encode($this->getItems());

                $js = <<<JS
new Phoenix.Field.ModalList('#{$this->getId()}-wrap', $items, $options);
JS;

                PhoenixScript::domready($js);

                if ($this->sortable()) {
                    PhoenixScript::sortableJS('#' . $this->getId() . '-wrap .modal-list-container', [
                        'handle' => '.drag-handle'
                    ]);
                }
            } else {
                PhoenixScript::select2('#' . $this->getId(), [
                    'placeholder' => $this->placeholder()
                ]);
            }
        }
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
            'imageField' => 'image_field',
            'titleClass',
            'function',
            'buttonText',
            'layout',
            'multiple',
            'listType' => 'list_type',
            'itemTemplate' => 'item_template',
            'hasImage' => 'has_image',
            'sortable',
            'placeholder',
            'onchange',
            'onfocus',
            'onblur',
            'height',
        ]);
    }
}
