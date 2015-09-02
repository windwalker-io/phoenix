{{-- Part of phoenix project. --}}

@if ($saveOrder)
<div class="input-group ordering-control">
    <input type="text" class="form-control input-xs" data-order-row="{{ $row }}" name="ordering[{{ $item->$pkName }}]" value="{{ $item->$orderField }}" onkeydown="if(event.keyCode == 13) return false;" />
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-xs" onclick="Phoenix.Grid.reorder({{ $row }}, -1);">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </button>
        <button type="button" class="btn btn-default btn-xs" onclick="Phoenix.Grid.reorder({{ $row }}, +1);">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </button>
    </div>
</div>
@else
{{ $item->$orderField }}
@endif