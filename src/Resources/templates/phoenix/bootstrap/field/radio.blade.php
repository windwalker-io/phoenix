{{-- Part of Phoenix project. --}}

<div id="{{ $field->getId() }}-group" class="form-group">
    <?php
    $field->appendAttribute('class', ' radio-container input-list-container ' . $field->get('fieldWidth', 'col-md-9'));
    $field->appendAttribute('labelClass', ' control-label ' . $field->get('labelWidth', 'col-md-3'));
    ?>
    {!! $field->renderLabel() !!}

    <div {!! \Windwalker\Dom\Builder\HtmlBuilder::buildAttributes($field->prepareAttributes()) !!}>
    <?php
    $radios = $field->renderInput();
    \Windwalker\Test\TestHelper::invoke($radios, 'prepareOptions');
    ?>
    @foreach($radios->getContent() as $option)
        <div class="radio">
            <?php $option[0]->setAttribute('disabled', (bool) $field->getAttribute('disabled')) ?>
            <?php $option[0]->setAttribute('readonly', (bool) $field->getAttribute('readonly')) ?>
            {!! $option[0]->setAttribute('style', 'margin-left: 0;') !!}
            {!! $option[1] !!}
        </div>
    @endforeach
    </div>
</div>
