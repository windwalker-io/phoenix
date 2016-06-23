{{-- Part of phoenix project. --}}

<div id="{{ $field->getId() }}-group" class="form-group">
    {!! $labelHtml !!}
    <div class="{{ $field->get('fieldWidth', 'col-md-9') }} input-container">
        {!! $inputHtml !!}
    </div>
</div>
