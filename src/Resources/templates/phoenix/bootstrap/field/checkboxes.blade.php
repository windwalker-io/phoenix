{{-- Part of Phoenix project. --}}


<?php
$field->set('class', $field->get('class') . ' form-control');
?>

<div id="{{$field->getId()}}" class="checkbox-container input-list-container {{ $field->get('fieldWidth', 'col-md-9') }}" {{ $field->get('required') ? 'required' : null }}>
<?php
$radios = $field->renderInput();
\Windwalker\Test\TestHelper::invoke($radios, 'prepareOptions');
?>
@foreach($radios->getContent() as $option)
    <div class="checkbox">
        <?php $option[0]->setAttribute('disabled', (bool) $field->getAttribute('disabled')) ?>
        <?php $option[0]->setAttribute('readonly', (bool) $field->getAttribute('readonly')) ?>
        {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
        {!! $option[1] !!}
    </div>
@endforeach
</div>
