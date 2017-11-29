{{-- Part of Phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::modal();
$disabled = $attrs['readonly'] || $attrs['disabled'];
?>

<div id="{{ $id }}-wrap">
    <div class="input-group">
        <input type="text"
            disabled="disabled"
            readonly="readonly"
            id="{{ $attrs['id'] }}-title"
            class="form-control {{ $field->get('titleClass') }}"
            value="{{ $title or '' }}"
            placeholder="{{ $attrs['placeholder'] }}"
        />
        <span class="input-group-btn">
            <a class="btn btn-info hasModal {{ $disabled ? 'disabled' : null }}" role="button" href="{{ $disabled ? 'javascript:void(0);' : $url }}">
                @translate($field->getAttribute('buttonText', 'phoenix.form.field.modal.button.text'))
            </a>
            @if (!$disabled)
                <a href="javascript://" role="button" class="btn btn-default btn-info unselect-button"
                    onclick="Phoenix.Field.Modal.select('#{{ $id }}-wrap', '', '')">
                <span class="fa fa-remove"></span>
            </a>
            @endif
        </span>
    </div>
    {!! $input !!}
</div>
