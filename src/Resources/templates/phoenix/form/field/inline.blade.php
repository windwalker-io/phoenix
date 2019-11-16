{{-- Part of phoenix project. --}}
<?php
/**
 * @var $form \Windwalker\Form\Form
 * @var $field \Phoenix\Field\InlineField
 * @var $form \Windwalker\Form\Form
 * @var $fields \Windwalker\Form\Field\AbstractField[]
 */

$bs4 = \Phoenix\Script\BootstrapScript::$currentVersion === 4;
$fields = $form->getFields('inline-' . $field->getName(true));
$showLabel = $field->showLabel() ?? false;
$count = count($fields);
$i = 1;
?>

<div id="{{ $field->getId() }}" class="inline-field-wrap {{ $bs4 ? 'd-flex' : 'form-inline' }}">
    @foreach ($fields as $subField)
        <?php
        $attrs = (array) $subField->get('controlAttribs', []);
        $attrs = \Windwalker\Utilities\Arr::def($attrs, 'id', $subField->getId() . '-control');
        $attrs = \Windwalker\Utilities\Arr::def($attrs, 'class', '');
        $attrs = \Windwalker\Utilities\Arr::def($attrs, 'style', '');

        $attrs['class'] = $subField->getType() . '-field ' . ($count === $i ? '' : 'mr-sm-3') . ' ' . $attrs['class'];
        $attrs['style'] = ($bs4 ? '' : 'display: inline-block;') . $attrs['style'];

        if ($subField->get('width') !== null) {
            $attrs['style'] .= 'width: ' . $subField->get('width') . ';';
        } else {
            $attrs['class'] .= ' flex-grow-1';
        }

        if (!$showLabel) {
            $subField->appendAttribute('labelClass', 'sr-only');
        }

        $subField->set('labelWidth', 'col-12');
        $subField->set('fieldWidth', 'col-12')
        ?>
        <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attrs) !!}>
            {!! $subField->render(['no_label' => false, 'vertical' => true]) !!}
        </div>
        @php($i++)
    @endforeach
</div>
