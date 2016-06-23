{{-- Part of Phoenix project. --}}

<div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attribs) !!}>
@php
/**
 * @var \Windwalker\Form\Field\CheckboxesField $field
 * @var \Windwalker\Html\Select\CheckboxList   $checkboxes
 */
$checkboxes = $field->buildInput($attribs);
$checkboxes->prepareOptions();
@endphp
@foreach($checkboxes->getContent() as $option)
    <div class="checkbox">
        @php( $option[0]->setAttribute('disabled', (bool) $field->getAttribute('disabled')) )
        @php( $option[0]->setAttribute('readonly', (bool) $field->getAttribute('readonly')) )
        {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
        {!! $option[1] !!}
    </div>
@endforeach
</div>
