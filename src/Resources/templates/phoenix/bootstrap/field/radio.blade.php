{{-- Part of Phoenix project. --}}

<?php
$vmodel = isset($attribs['v-model']) ? $attribs['v-model'] : false;
$attribs['v-model'] = false;
?>

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
            @php( $option[0]->setAttribute('v-model', $vmodel) )
            {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
            {!! $option[1] !!}
        </div>
    @endforeach
</div>
