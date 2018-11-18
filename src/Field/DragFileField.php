<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Field;

use Phoenix\Script\PhoenixScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Form\Field\FileField;

/**
 * The DragFileField class.
 *
 * @method  mixed|$this  maxFiles(int $value = null)
 * @method  mixed|$this  maxSize(int $value = null)
 * @method  mixed|$this  accepted(string $value = null)
 * @method  mixed|$this  layout(string $value = null)
 *
 * @since  1.7.3
 */
class DragFileField extends FileField
{
    /**
     * Property inited.
     *
     * @var  bool
     */
    protected static $inited = false;

    /**
     * prepareAttributes
     *
     * @return  array
     */
    public function prepareAttributes()
    {
        $this->class('custom-file-input');

        return parent::prepareAttributes();
    }

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  string
     */
    public function buildInput($attrs)
    {
        if ($this->multiple()) {
            $attrs['data-max-files'] = $this->maxFiles();
        }

        $attrs['data-max-size'] = $this->maxSize();

        $input = parent::buildInput($attrs);
        $id    = $this->getId();

        $this->prepareScript();

        return WidgetHelper::render($this->get('layout', 'phoenix.form.field.drag-file'), [
            'id' => $id,
            'input' => $input,
            'attrs' => $attrs,
            'field' => $this,
        ], WidgetHelper::EDGE);
    }

    /**
     * prepareScript
     *
     * @return  void
     *
     * @since  1.7.3
     */
    protected function prepareScript()
    {
        if (!static::$inited) {
            Asset::addJS(PhoenixScript::phoenixName() . '/js/field/drag-file.min.js');
            Asset::addCSS(PhoenixScript::phoenixName() . '/css/field/drag-file.min.css');

            PhoenixScript::translate('phoenix.form.field.drag.file.placeholder.single');
            PhoenixScript::translate('phoenix.form.field.drag.file.placeholder.multiple');
            PhoenixScript::translate('phoenix.form.field.drag.file.selected');
            PhoenixScript::translate('phoenix.form.field.drag.file.message.max.files');
            PhoenixScript::translate('phoenix.form.field.drag.file.message.unaccepted.files');
            PhoenixScript::translate('phoenix.form.field.drag.file.message.unaccepted.files.desc');
            PhoenixScript::translate('phoenix.form.field.drag.file.message.max.size');

            static::$inited = true;
        }

        $id = $this->getId();

        $js = <<<JS
$('#$id').dragFile();
JS;

        PhoenixScript::domready($js);
    }

    /**
     * getAccessors
     *
     * @return  array
     */
    protected function getAccessors()
    {
        return array_merge(parent::getAccessors(), [
            'accepted',
            'maxFiles' => 'max_files',
            'maxSize' => 'max_size',
            'layout',
        ]);
    }
}
