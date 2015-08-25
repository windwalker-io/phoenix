
<div class="search-container form-inline">
    {{ $form->getField('field', 'search')->appendAttribute('class', ' form-control')->renderInput() }}

    <div class="input-group">
        {{ $form->getField('content', 'search')->appendAttribute('class', ' form-control')->renderInput() }}
        <span class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
        </span>
    </div>
    <button type="button" class="btn btn-default filter-toggle-button">Filters</button>
    <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></button>
</div>

<div class="filter-container row">
@foreach($form->getFields(null, 'filter') as $field)
    <div class="form-group col-md-3">
        {{ $field->appendAttribute('labelClass', ' hide')->renderLabel() }}
        {{ $field->appendAttribute('class', ' form-control')->renderInput() }}
    </div>
@endforeach
</div>
