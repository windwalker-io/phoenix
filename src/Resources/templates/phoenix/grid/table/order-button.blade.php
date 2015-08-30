{{-- Part of phoenix project. --}}

<div class="input-group ordering-control">
    <input type="text" class="form-control input-xs" data-order-row="{{ $row }}" name="ordering[{{ $item->$pkName }}]" value="{{ $item->$orderField }}" />
    <div class="input-group-btn">
        <button type="button" class="btn btn-default btn-xs" onclick="Phoenix.setOrder({{ $row }}, -1);">
            <span class="glyphicon glyphicon-chevron-up"></span>
        </button>
        <button type="button" class="btn btn-default btn-xs" onclick="Phoenix.setOrder({{ $row }}, +1);">
            <span class="glyphicon glyphicon-chevron-down"></span>
        </button>
    </div>
</div>
