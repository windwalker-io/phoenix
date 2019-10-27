<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2018 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Phoenix\Field;

use Phoenix\Script\PhoenixScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Data\Collection;
use Windwalker\Form\Field\FileField;
use Windwalker\String\Str;
use function Windwalker\arr;

/**
 * The DragFileField class.
 *
 * @method  mixed|$this  maxFiles(int $value = null)
 * @method  mixed|$this  maxSize(int $value = null)
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
        $this->addClass('custom-file-input');

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

        // Fix accept
        if (trim($attrs['accept'])) {
            $attrs['accept'] = Collection::explode(',', $attrs['accept'])
                ->map('trim')
                ->map(function ($type) {
                    if (strpos($type, '/') === false) {
                        return Str::ensureLeft($type, '.');
                    }

                    return $type;
                })
                ->implode(',');
        }

        $input = parent::buildInput($attrs);

        $this->prepareScript($attrs);

        return WidgetHelper::render($this->get('layout', 'phoenix.form.field.drag-file'), [
            'id' => $attrs['id'],
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
    protected function prepareScript(array $attrs)
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

        $id = $attrs['id'];

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
            'maxFiles' => 'max_files',
            'maxSize' => 'max_size',
            'layout',
        ]);
    }
}
