{{-- Part of Phoenix project. --}}

@if ($saveOrder)
    <div class="input-group ordering-control">
        <input type="text" class="form-control input-sm form-control-sm" data-order-row="{{ $row }}"
               name="ordering[{{ $item->$keyName }}]" value="{{ $item->$orderField }}"
               onkeydown="if(event.keyCode == 13) return false;"/>
        <div class="input-group-btn">
            <button type="button" class="btn btn-default btn-outline-secondary btn-sm hasTooltip"
                    onclick="{{ $phoenixJsObject }}.Grid.reorder({{ $row }}, -1);"
                    title="@translate('phoenix.grid.ordering.moveup')">
                <span class="fa fa-chevron-up"></span>
            </button>
            <button type="button" class="btn btn-default btn-outline-secondary btn-sm hasTooltip"
                    onclick="{{ $phoenixJsObject }}.Grid.reorder({{ $row }}, +1);"
                    title="@translate('phoenix.grid.ordering.movedown')">
                <span class="fa fa-chevron-down"></span>
            </button>
        </div>
    </div>
@else
    {{ $item->$orderField }}
@endif
