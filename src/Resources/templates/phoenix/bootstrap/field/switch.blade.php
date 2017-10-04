{{-- Part of Phoenix project. --}}

<?php
/**
 * @var $field \Phoenix\Field\SwitchField
 */

$vmodel = isset($attribs['v-model']) ? $attribs['v-model'] : false;
$attribs['v-model'] = false;
$color = $field->get('color', 'primary');
?>
<label class="phoenix-switch">
        <input name="{{ $field->getFieldName() }}" type="hidden" value="{{ $field->get('unchecked_value', 0) }}" />
    {!! $field->buildInput($attribs) !!}
    <span class="slider {{ $field->get('round') ? 'round' : '' }} {{ $color ? 'btn-' . $color : 'btn-default' }}"></span>
</label>
