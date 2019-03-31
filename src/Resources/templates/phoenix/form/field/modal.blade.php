{{-- Part of Phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::modal();
$disabled = $attrs['readonly'] || $attrs['disabled'];
?>

<div id="{{ $id }}-wrap">
    @if (!$disabled)
        <div class="input-group">
            @if ($field->get('has_image'))
                <div class="input-group-prepend modal-field-image">
                    <div class="input-group-text">
                        <div class="modal-field-image-preview"
                            style="height: 1.5em; width: 1.5em; border-radius: 2px; background: url({{ $image }}) center center; background-size: cover">

                        </div>
                    </div>
                </div>
            @endif

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
                        <a href="javascript://" role="button" class="btn btn-info unselect-button"
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
