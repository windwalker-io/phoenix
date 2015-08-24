
<div class="search-container form-inline">
    <div class="form-group">
        {{ $form->getField('field', 'search')->appendAttribute('class', 'form-control')->renderInput() }}
    </div>
    <div class="form-group">
        {{ $form->getField('content', 'search')->appendAttribute('class', 'form-control')->renderInput() }}
    </div>
</div>

<div class="filter-container row">
@foreach($form->getFields(null, 'filter') as $field)
    <div class="form-group col-md-3">
        {{ $field->appendAttribute('labelClass', ' hide')->renderLabel() }}
        {{ $field->appendAttribute('class', ' form-control')->renderInput() }}
    </div>
@endforeach
</div>
