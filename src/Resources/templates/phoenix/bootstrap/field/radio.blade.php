{{-- Part of Phoenix project. --}}

<div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($attribs) !!}>
@php
/** @var \Windwalker\Html\Select\RadioList $radios */
$radios = $field->buildInput($attribs);
$radios->prepareOptions();
@endphp
@foreach($radios->getContent() as $option)
    <div class="radio">
    @php( $option[0]->setAttribute('disabled', (bool) $field->getAttribute('disabled')) )
    @php( $option[0]->setAttribute('readonly', (bool) $field->getAttribute('readonly')) )
    {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
    {!! $option[1] !!}
</div>
@endforeach
</div>
