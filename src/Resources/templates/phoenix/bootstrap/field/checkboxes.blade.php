{{-- Part of phoenix project. --}}

<div class="form-group">
    <?php
    $field->set('class', $field->get('class') . ' form-control');
    $field->set('labelClass', $field->get('labelClass') . ' control-label ' . $field->get('labelWidth', 'col-md-3'));
    ?>
    {!! $field->renderLabel() !!}

    <div id="{{$field->getId()}}" class="checkbox-container input-list-container {{ $field->get('fieldWidth', 'col-md-9') }}" {{{ $field->get('required') ? 'required' : null }}}>
    <?php
    $radios = $field->renderInput();
    \Windwalker\Test\TestHelper::invoke($radios, 'prepareOptions');
    ?>
    @foreach($radios->getContent() as $option)
        <div class="checkbox">
            {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
            {!! $option[1] !!}
        </div>
    @endforeach
    </div>
</div>
