{{-- Part of phoenix project. --}}
<?php
/**
 * @var  \Windwalker\Form\Field\AbstractField  $field
 */
?>

@php(\Phoenix\Script\BootstrapScript::calendar('#' . $id . '-wrapper', $format, $options))

<div id="{{ $id }}-wrapper" class="{{ $field->get('disabled') ? '' : 'input-group' }} date datetime-picker">
    {!! $input !!}

    @if (!$field->get('disabled'))
        <span class="input-group-addon">
            <span class="glyphicon glyphicon-calendar fa fa-calendar"></span>
        </span>
    @endif
</div>
