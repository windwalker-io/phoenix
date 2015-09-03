
<div class="search-container form-inline">
    {{ $form->getField('field', 'search')->appendAttribute('class', ' form-control')->renderInput() }}

    <div class="input-group">
        {{ $form->getField('content', 'search')->appendAttribute('class', ' form-control')->renderInput() }}
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
        </span>
    </div>
    <button type="button" class="btn btn-default filter-toggle-button {{ $show ? 'btn-primary' : null }}">
        @translate('phoenix.grid.filter.button')
        <span class="glyphicon glyphicon-menu-{{ $show ? 'up' : 'down' }}"></span>
    </button>
    <button type="button" class="btn btn-default search-clear-button"><span class="glyphicon glyphicon-remove"></span></button>
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
    <div class="form-group col-md-3">
        {{ $field->appendAttribute('labelClass', 'hide')->renderLabel() }}
        {{ $field->appendAttribute('class', 'form-control')->renderInput() }}
    </div>
    @endif
@endforeach
</div>
