{{-- Part of Phoenix project. --}}

<?php
/**
 * @var $field   \Phoenix\Field\SwitchField
 * @var $attribs array
 */

$color = $field->get('color', 'primary');

if ($field->attr('v-model')) {
    $attribs[':true-value']  = $field->get('unchecked_value', 0);
    $attribs[':false-value'] = $field->get('checked_value', 1);
}
?>
<label class="phoenix-switch" for="{{ $field->getId() }}">
    <input id="{{ $attribs['id'] }}-unchecked" name="{{ $field->getFieldName() }}" type="hidden"
        value="{{ $field->get('unchecked_value', 0) }}"
        {{ $field->get('disabled') ? 'disabled="disabled"' : '' }}
    />
    {!! $field->buildInput($attribs) !!}
    <span
        class="switch-slider {{ $field->get('shape', 'slider-square') }} {{ $color ? 'btn-' . $color : 'btn-default' }}"></span>
</label>
