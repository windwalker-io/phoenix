{{-- Part of Phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::modal();
$disabled = $attrs['readonly'] || $attrs['disabled'];
?>

<div id="{{ $id }}-wrap">
    <div class="input-group">
        {!! $input !!}
        <span class="input-group-btn">
            <a class="btn btn-info hasModal {{ $disabled ? 'disabled' : null }}" type="button" href="{{ $disabled ? 'javascript:void(0);' : $url }}">
                @translate($field->getAttribute('buttonText', 'phoenix.form.field.modal.button.text'))
            </a>
        </span>
    </div>
    <input type="hidden" data-value-store name="{{ $attrs['name'] }}" value="{{ $attrs['value'] }}" />
</div>
