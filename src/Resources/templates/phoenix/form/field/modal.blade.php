{{-- Part of Phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::modal();
$disabled = $attrs['readonly'] || $attrs['disabled'];
?>

<div id="{{ $id }}-wrap">
    @if (!$disabled)
        <div class="input-group">
            @endif
            <input type="text"
                disabled="disabled"
                readonly="readonly"
                id="{{ $attrs['id'] }}-title"
                class="form-control {{ $field->get('titleClass') }}"
                value="{{ $title or '' }}"
                placeholder="{{ $attrs['placeholder'] }}"
            />
            @if (!$disabled)
                <span class="input-group-btn input-group-append">
            <a class="btn btn-info hasModal {{ $disabled ? 'disabled' : null }}" role="button"
                href="{{ $disabled ? 'javascript:void(0);' : $url }}">
                @lang($field->getAttribute('buttonText', 'phoenix.form.field.modal.button.text'))
            </a>

                    @if (!$attrs['required'])
                        <a href="javascript://" role="button" class="btn btn-default btn-info unselect-button"
                            onclick="Phoenix.Field.Modal.select('#{{ $id }}-wrap', '', '')">
                    <span class="fa fa-remove fa-times"></span>
                </a>
                    @endif
        </span>
            @endif
            @if (!$disabled)
        </div>
    @endif
    {!! $input !!}
</div>
