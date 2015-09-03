{{-- Part of phoenix project. --}}

<?php
\Phoenix\Script\BootstrapScript::modal();
?>

<div id="{{ $id }}-wrap">
    <div class="input-group">
        {{ $input }}
        <span class="input-group-btn">
            <a class="btn btn-info hasModal" type="button" href="{{ $url }}">
                @translate($field->getAttribute('buttonText', 'phoenix.form.field.modal.button.text'))
            </a>
        </span>
    </div>
    <input type="hidden" data-value-store name="{{ $attrs['name'] }}" value="{{ $attrs['value'] }}" />
</div>
