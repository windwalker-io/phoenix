{{-- Part of Phoenix project. --}}

<?php
$vmodel = isset($attribs['v-model']) ? $attribs['v-model'] : false;
$attribs['v-model'] = false;
?>

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
            @php( $option[0]->setAttribute('v-model', $vmodel) )
            @php( $option[0]->setAttribute('class', $option[0]->getAttribute('class') . ' form-check-input') )
            @php( $option[1]->setAttribute('class', $option[1]->getAttribute('class') . ' form-check-label') )
            {!! $option[0] !!}
            {!! $option[1] !!}
        </div>
    @endforeach
</div>
