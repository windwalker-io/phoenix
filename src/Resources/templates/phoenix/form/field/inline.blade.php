{{-- Part of phoenix project. --}}
<?php
/**
 * @var $form \Windwalker\Form\Form
 */
?>

<div id="{{ $field->getId() }}" class="inline-field-wrap form-inline">
    @foreach ($form->getFields('inline-' . $field->getName()) as $subField)
        <div id="{{ $subField->getId() }}-control" class="{{ $subField->getType() }}-field mr-sm-3" style="display: inline-block;">
            {!! $subField->appendAttribute('labelClass', 'sr-only')->renderLabel() !!}
            {!! $subField->renderInput() !!}
        </div>
    @endforeach
</div>

