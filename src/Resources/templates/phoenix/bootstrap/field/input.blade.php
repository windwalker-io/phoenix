{{-- Part of phoenix project. --}}

<div class="form-group">
    <?php
    $field->set('class', $field->get('class') . ' form-control');
    $field->set('labelClass', $field->get('labelClass') . ' control-label ' . $field->get('labelWidth', 'col-md-3'));
    ?>
    {{ $field->renderLabel() }}
    <div class="{{ $field->get('fieldWidth', 'col-md-9') }}">
        {!! $field->renderInput() !!}
    </div>
</div>
