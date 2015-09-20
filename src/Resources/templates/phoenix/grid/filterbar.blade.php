
<h3 class="visible-xs-block">
    @translate('phoenix.grid.filter.title')
</h3>

<div class="search-container form-inline">
    <?php $field = $form->getField('field', 'search'); ?>
    {!! $field->appendAttribute('labelClass', ' sr-only')->renderLabel() !!}

    @if ($field->get('display'))
    {!! $field->appendAttribute('class', ' form-control')->renderInput() !!}
    @else
    <input id="{{ $field->getId() }}" name="{{ $field->getFieldName() }}" value="{{ $field->getValue() }}" type="hidden" />
    @endif

    {!! $form->getField('content', 'search')->appendAttribute('labelClass', ' sr-only')->renderLabel() !!}
    <div class="input-group">
        {!! $form->getField('content', 'search')->appendAttribute('class', ' form-control')->renderInput() !!}
        <span class="input-group-btn">
            <button class="btn btn-default hasTooltip" type="submit"
                title="@translate('phoenix.grid.search.button.desc')">
                <span class="glyphicon glyphicon-search fa fa-search"></span>
            </button>
        </span>
    </div>
    <div class="btn-group filter-buttons-group">
        <button type="button" class="btn btn-default filter-toggle-button hasTooltip {{ $show ? 'btn-primary' : null }}"
            title="@translate('phoenix.grid.filter.button.desc')">
            @translate('phoenix.grid.filter.button.text')
            <span class="glyphicon glyphicon-menu-{{ $show ? 'up' : 'down' }} fa fa-angle-{{ $show ? 'up' : 'down' }}"></span>
        </button>
        <button type="button" class="btn btn-default search-clear-button hasTooltip"
            title="@translate('phoenix.grid.clear.button.desc')">
            <span class="glyphicon glyphicon-remove fa fa-remove"></span>
        </button>
    </div>
</div>

<div class="filter-container row {{ $show ? 'shown' : null }}" style="{{ $show ? null : 'display: none;' }}">
@foreach($form->getFields(null, 'filter') as $field)

    @if ($field->getType() == 'spacer')
        <div class="col-md-12">
            @if ($field->getLabel())
                <h4>{{ $field->getLabel() }}</h4>
            @endif

            @if ($field->get('description'))
                <p>{{ $field->get('description') }}</p>
            @endif
        </div>
    @else
    <div class="form-group col-sm-4 col-md-3">
        {!! $field->appendAttribute('labelClass', ' sr-only')->renderLabel() !!}
        {!! $field->appendAttribute('class', ' form-control')->renderInput() !!}
    </div>
    @endif
@endforeach
</div>
