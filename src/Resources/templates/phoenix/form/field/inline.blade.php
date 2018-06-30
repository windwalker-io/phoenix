{{-- Part of phoenix project. --}}
<?php
/**
 * @var $form \Windwalker\Form\Form
 */

$bs4 = \Phoenix\Script\BootstrapScript::$currentVersion === 4;
$fields = $form->getFields('inline-' . $field->getName(true));
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

        $attrs['class'] = $subField->getType() . '-field flex-grow-1 ' . ($count === $i ? '' : 'mr-sm-3') . ' ' . $attrs['class'];
        $attrs['style'] = ($bs4 ? '' : 'display: inline-block;') . $attrs['style'];
        ?>
        <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attrs) !!}>
            {!! $subField->appendAttribute('labelClass', 'sr-only')->renderLabel() !!}
            {!! $subField->renderInput() !!}
        </div>
        @php($i++)
    @endforeach
</div>
